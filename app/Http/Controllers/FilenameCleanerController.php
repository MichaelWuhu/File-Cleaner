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

        $lowercase = $request->has('lowercase');
        $hyphens = $request->has('hyphens');
        $dedupe = $request->has('dedupe');
        $removeCopy = $request->has('remove_copy');

        $results = [];

        foreach ($lines as $line) {
            $original = trim($line);

            if ($original === '') {
                continue;
            }

            $results[] = [
                'original' => $original,
                'cleaned' => $this->cleanFilename(
                    $original,
                    $lowercase,
                    $hyphens,
                    $dedupe,
                    $removeCopy
                ),
            ];
        }

        return view('cleaner', [
            'input' => $request->filenames,
            'results' => $results,
            'lowercase' => $lowercase,
            'hyphens' => $hyphens,
            'dedupe' => $dedupe,
            'removeCopy' => $removeCopy,
        ]);
    }

    private function cleanFilename(
        string $filename,
        bool $lowercase,
        bool $hyphens,
        bool $dedupe,
        bool $removeCopy
    ): string {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);

        if ($lowercase) {
            $name = strtolower($name);
        }

        if ($removeCopy) {
            $name = preg_replace('/\(\d+\)$/', '', $name);
        }

        if ($hyphens) {
            $name = preg_replace('/[_\s]+/', '-', $name);
        }

        $name = preg_replace('/-+/', '-', $name);
        $name = preg_replace('/[^a-zA-Z0-9\-]/', '', $name);
        $name = trim($name, '-');

        if ($dedupe) {
            $parts = explode('-', $name);
            $deduped = [];

            foreach ($parts as $part) {
                if ($part !== '' && !in_array($part, $deduped, true)) {
                    $deduped[] = $part;
                }
            }

            $name = implode('-', $deduped);
        }

        $extension = strtolower($extension);

        return $extension ? "{$name}.{$extension}" : $name;
    }
}
