<?php
//https://gist.github.com/megasaturnv/81279fca49f2f34b42e77815c9bb1eb8
//Megasaturnv 2018-01-12
$camurl="http://127.0.0.1:8081/";                           // Mjpeg URL
//$camurl="http://username:password@192.168.1.100:8081/"        // HTTP Auth mjpeg URL (optional)
$boundary="--BoundaryString";                                   // $boundary = The boundary string between jpegs in an mjpeg stream
//NOTE: $boundary changes between mjpeg stream providers. For example, https://github.com/Motion-Project/motion uses '--BoundaryString'. https://github.com/ZoneMinder/ZoneMinder uses '--ZoneMinderFrame'. To find out $boundary for your stream you will need to save 1 or more frames of the mjpeg and open it with a text editor. --<boundary string> should be visible on the first line
$f = fopen($camurl, "r");                                       // Open mjpeg url as $f in readonly mode
$r = "";                                                        // Set $r to blank variable

if($f) {
    while (substr_count($r,"Content-Length") < 2)               // While the number of times "Content-Length" appears in $r is less than 2
        $r.=fread($f, 16384);                                   // Append 16384 bytes of $f to $r

    $start = strpos("$r", $boundary);                           // $start is set to the position of the first occurrence of '$boundary' in $r
    $end = strpos("$r", $boundary, $start + strlen($boundary)); // $end is essentially set to the position of the second occurrence of '$boundary' in $r. I use $start + strlen($boundary) to offset the start position and skip the first occurrence
    $boundaryAndFrame = substr("$r", $start, $end - $start);    // $boundaryAndframe is set to the string in $r starting at position $start and with a length of ($end - $start)

    $pattern="/(Content-Length:\s*\d*\r\n\r\n)([\s\S]*)/";      // Use regex to search for '(Content-Length:       90777\r\n\r\n)(<jpeg image data>)
    preg_match_all($pattern, $boundaryAndFrame, $matches);      // Search for regex matches in $boundaryAndFrame
    $frame = $matches[2][0];                                    // $frame is set to the second regex character group (in this case, <jpeg image data>)
    header("Content-type: image/jpeg");                         // Set header for jpeg image
    #header("Cache-Control: no-cache");
    header("Cache-Control: no-store");
    #header("Pragma: no-cache");
    echo $frame;                                                // Echo the jpeg image data
} else {
    echo "Error, cannot open URL";                              // Error message if $camurl cannot be opened
}

fclose($f);                                                     // Close the url
?>
