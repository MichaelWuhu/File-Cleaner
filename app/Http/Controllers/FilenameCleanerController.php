<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilenameCleanerController extends Controller
{
    public function index()
    {
        return view('cleaner');
    }

    public function clean(Request $request)
    {
        $request->validate([
            'filenames' => 'required|string',
        ]);

        $lines = preg_split('/\r\n|\r|\n/', trim($request->filenames));
        $results = [];

        foreach ($lines as $line) {
            $original = trim($line);

            if ($original === '') {
                continue;
            }

            $results[] = [
                'original' => $original,
                'cleaned' => $this->cleanFilename($original),
            ];
        }

        return view('cleaner', [
            'input' => $request->filenames,
            'results' => $results,
        ]);
    }

    private function cleanFilename(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);

        $name = strtolower($name);

        $name = preg_replace('/\(\d+\)$/', '', $name);
        $name = preg_replace('/[_\s]+/', '-', $name);
        $name = preg_replace('/-+/', '-', $name);
        $name = preg_replace('/[^a-z0-9\-]/', '', $name);
        $name = trim($name, '-');

        $parts = explode('-', $name);
        $deduped = [];

        foreach ($parts as $part) {
            if ($part !== '' && !in_array($part, $deduped, true)) {
                $deduped[] = $part;
            }
        }

        $name = implode('-', $deduped);
        $extension = strtolower($extension);

        return $extension ? "{$name}.{$extension}" : $name;
    }
}