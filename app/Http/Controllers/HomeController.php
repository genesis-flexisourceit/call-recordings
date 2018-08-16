<?php

namespace App\Http\Controllers;

use App\Recording;
use App\Transcription;
use Google\Cloud\Core\ExponentialBackoff;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Http\Request;
use Google\Cloud\Speech\SpeechClient;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * @var string
     */
    protected $projectId = 'techtest-209407';
    protected $bucketName = 'developer-test-1';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $recordings = Recording::with('transcription')->get();

        return view('home')->with(['recordings' => $recordings]);
    }

    /**
     * @param $id
     *
     * @return array
     * @throws \Exception
     */
    public function transcribe($id)
    {
        $recording = Recording::with('transcription')->find($id);

        if(!$recording)
            return response(['message' => 'The call recording you\'re trying to access does not exist.'], 403);

        if($recording->transcription)
            return response(['message' => $recording->transcription->words], 200);

        $speech = new SpeechClient([
            'projectId' => $this->projectId,
            'languageCode' => 'en-US',
            'keyFilePath' => '../key.json'
        ]);
        # The audio file's encoding and sample rate
        $options = [];

        $storage = new StorageClient();

        $object = $storage->bucket($this->bucketName)->object($recording->path);

        // Create the asyncronous recognize operation
        $operation = $speech->beginRecognizeOperation(
            $object,
            $options
        );

        // Wait for the operation to complete
        $backoff = new ExponentialBackoff(10);
        $backoff->execute(function () use ($operation) {
            //print('Waiting for operation to complete' . PHP_EOL);
            $operation->reload();
            if (!$operation->isComplete()) {
                throw new \Exception('Job has not yet completed', 500);
            }
        });

        // Print the results
        if ($operation->isComplete()) {
            $results = $operation->results();

            $lines = [];
            foreach ($results as $result) {
                $alternative = $result->alternatives()[0];
                $lines[] = $alternative['transcript'];

                /*printf('Transcript: %s' . PHP_EOL, $alternative['transcript']);*/
            }

            $recording->transcription()->save(new Transcription(['words' => implode("<br/>", $lines)]));

            return response(['message' => implode("<br/>", $lines)], 200);
        }
    }
}
