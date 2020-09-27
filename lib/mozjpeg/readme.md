## windows 编译版本来源: [](https://github.com/garyzyg/mozjpeg-windows/releases)

> MozJPEG 只支持输入 TGA、BMP、PPM、JPEG 四种格式的图片，不支持 PNG

### cjpeg 图片压缩
```
usage: D:\www\tp6\vendor\bingher\zipimg\lib\mozjpeg\cjpeg-static.exe [switches] [inputfile]
Switches (names may be abbreviated):
  -quality N[,...]   Compression quality (0..100; 5-95 is most useful range,
                     default is 75)
  -grayscale     Create monochrome JPEG file
  -rgb           Create RGB JPEG file
  -optimize      Optimize Huffman table (smaller file, but slow compression, enabled by default)
  -progressive   Create progressive JPEG file (enabled by default)
  -baseline      Create baseline JPEG file (disable progressive coding)
  -targa         Input file is Targa format (usually not needed)
  -revert        Revert to standard defaults (instead of mozjpeg defaults)
  -fastcrush     Disable progressive scan optimization
  -dc-scan-opt   DC scan optimization mode
                 - 0 One scan for all components
                 - 1 One scan per component (default)
                 - 2 Optimize between one scan for all components and one scan for 1st component
                     plus one scan for remaining components
  -notrellis     Disable trellis optimization
  -trellis-dc    Enable trellis optimization of DC coefficients (default)
  -notrellis-dc  Disable trellis optimization of DC coefficients
  -tune-psnr     Tune trellis optimization for PSNR
  -tune-hvs-psnr Tune trellis optimization for PSNR-HVS (default)
  -tune-ssim     Tune trellis optimization for SSIM
  -tune-ms-ssim  Tune trellis optimization for MS-SSIM
Switches for advanced users:
  -noovershoot   Disable black-on-white deringing via overshoot
  -arithmetic    Use arithmetic coding
  -dct int       Use integer DCT method (default)
  -dct float     Use floating-point DCT method
  -quant-baseline Use 8-bit quantization table entries for baseline JPEG compatibility
  -quant-table N Use predefined quantization table N:
                 - 0 JPEG Annex K
                 - 1 Flat
                 - 2 Custom, tuned for MS-SSIM
                 - 3 ImageMagick table by N. Robidoux
                 - 4 Custom, tuned for PSNR-HVS
                 - 5 Table from paper by Klein, Silverstein and Carney
  -restart N     Set restart interval in rows, or in blocks with B
  -smooth N      Smooth dithered input (N=1..100 is strength)
  -maxmemory N   Maximum memory to use (in kbytes)
  -outfile name  Specify name for output file
  -memdst        Compress to memory instead of file (useful for benchmarking)
  -verbose  or  -debug   Emit debug output
  -version       Print version information and exit
Switches for wizards:
  -qtables FILE  Use quantization tables given in FILE
  -qslots N[,...]    Set component quantization tables
  -sample HxV[,...]  Set component sampling factors
  -scans FILE    Create multi-scan JPEG per script FILE
```

### djpeg 图片格式转换
```
usage: D:\www\tp6\vendor\bingher\zipimg\lib\mozjpeg\djpeg-static.exe [switches] [inputfile]
Switches (names may be abbreviated):
  -colors N      Reduce image to no more than N colors
  -fast          Fast, low-quality processing
  -grayscale     Force grayscale output
  -rgb           Force RGB output
  -rgb565        Force RGB565 output
  -scale M/N     Scale output image by fraction M/N, eg, 1/8
  -gif           Select GIF output format
  -os2           Select BMP output format (OS/2 style)
  -pnm           Select PBMPLUS (PPM/PGM) output format (default)
  -targa         Select Targa output format
Switches for advanced users:
  -dct int       Use integer DCT method (default)
  -dct fast      Use fast integer DCT (less accurate)
  -dct float     Use floating-point DCT method
  -dither fs     Use F-S dithering (default)
  -dither none   Don't use dithering in quantization
  -dither ordered  Use ordered dither (medium speed, quality)
  -map FILE      Map to colors used in named image file
  -nosmooth      Don't use high-quality upsampling
  -onepass       Use 1-pass quantization (fast, low quality)
  -maxmemory N   Maximum memory to use (in kbytes)
  -outfile name  Specify name for output file
  -memsrc        Load input file into memory before decompressing
  -skip Y0,Y1    Decompress all rows except those between Y0 and Y1 (inclusive)
  -crop WxH+X+Y  Decompress only a rectangular subregion of the image
  -verbose  or  -debug   Emit debug output
  -version       Print version information and exit
```
