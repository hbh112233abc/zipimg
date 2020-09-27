<?php

/**
 * Optimizes PNG file with pngquant 1.8 or later (reduces file size of 24-bit/32-bit PNG images).
 *
 * You need to install pngquant 1.8 on the server (ancient version 1.0 won't work).
 * There's package for Debian/Ubuntu and RPM for other distributions on http://pngquant.org
 *
 * @param string $path_to_png_file  path to any PNG file, e.g. $_FILE['file']['tmp_name']
 * @param int    $max_quality       conversion quality, useful values from 60 to 100 (smaller number = smaller file)
 *
 * @return string - content of PNG file after conversion
 */
function compress_png($path_to_png_file, $max_quality = 90)
{
    if (!file_exists($path_to_png_file)) {
        throw new Exception("File does not exist: $path_to_png_file");
    }

    // guarantee that quality won't be worse than that.
    $min_quality = 60;

    // '-' makes it use stdout, required to save to $compressed_png_content variable
    // '<' makes it read from the given file path
    // escapeshellarg() makes this safe to use with any path
    $compressed_png_content = shell_exec("pngquant --quality=$min_quality-$max_quality - < " . escapeshellarg($path_to_png_file));

    if (!$compressed_png_content) {
        throw new Exception("Conversion to compressed PNG failed. Is pngquant 1.8+ installed on the server?");
    }

    return $compressed_png_content;
}

//Example:Compress uploaded PNG file
//You can compress a PNG file when it's uploaded: (remember to change name in $_FILE[] and $save_to_path)
$read_from_path = $_FILE['file']['tmp_name'];
$save_to_path   = "uploads/compressed_file.png";

$compressed_png_content = compress_png($read_from_path);
file_put_contents($save_to_path, $compressed_png_content);

// you don't need move_uploaded_file(). file_put_contents() will do that instead.

$path_to_uncompressed_file = 'test.png';
$path_to_compressed_file   = 'test-small.png';

//Example:Convert PNG files on the fly

//You optimize PNG files that are already on disk:
// this will ensure that $path_to_compressed_file points to compressed file
// and avoid re-compressing if it's been done already
if (!file_exists($path_to_compressed_file)) {
    file_put_contents($path_to_compressed_file, compress_png($path_to_uncompressed_file));
}

// and now, for example, you can output the compressed file:
header("Content-Type: image/png");
header('Content-Length: ' . filesize($path_to_compressed_file));
readfile($path_to_compressed_file);
