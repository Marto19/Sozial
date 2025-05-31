<?php
namespace Utils;

class FileUpload {
    private $uploadPath;
    private $allowedTypes;
    private $maxSize;
    private $file;

    public function __construct(string $uploadPath, array $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'], int $maxSize = 5242880) {
        $this->uploadPath = rtrim($uploadPath, '/') . '/';
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize;
    }

    public function setFile(array $file): void {
        $this->file = $file;
    }

    public function validate(): array {
        $errors = [];

        if (!isset($this->file['error']) || is_array($this->file['error'])) {
            $errors[] = 'Invalid file parameters.';
            return $errors;
        }

        switch ($this->file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $errors[] = 'File size exceeds limit.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $errors[] = 'File was only partially uploaded.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $errors[] = 'No file was uploaded.';
                break;
            default:
                $errors[] = 'Unknown file upload error.';
        }

        if ($this->file['size'] > $this->maxSize) {
            $errors[] = 'File size exceeds limit.';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $this->file['tmp_name']);
        finfo_close($finfo);

        $ext = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedTypes)) {
            $errors[] = 'Invalid file type.';
        }

        return $errors;
    }

    public function upload(): string {
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }

        $fileName = $this->generateUniqueFileName();
        $destination = $this->uploadPath . $fileName;

        if (!move_uploaded_file($this->file['tmp_name'], $destination)) {
            throw new \RuntimeException('Failed to move uploaded file.');
        }

        return $fileName;
    }

    public function resizeImage(string $filePath, int $maxWidth = 800, int $maxHeight = 600): void {
        list($width, $height) = getimagesize($filePath);
        
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return;
        }

        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);

        $sourceImage = $this->createImageFromFile($filePath);
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        imagecopyresampled(
            $newImage, 
            $sourceImage, 
            0, 0, 0, 0, 
            $newWidth, $newHeight, 
            $width, $height
        );

        $this->saveImage($newImage, $filePath);

        imagedestroy($sourceImage);
        imagedestroy($newImage);
    }

    private function generateUniqueFileName(): string {
        $ext = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        return uniqid() . '.' . $ext;
    }

    private function createImageFromFile(string $filePath) {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($filePath);
            case 'png':
                return imagecreatefrompng($filePath);
            case 'gif':
                return imagecreatefromgif($filePath);
            default:
                throw new \RuntimeException('Unsupported image type.');
        }
    }

    private function saveImage($image, string $filePath): void {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($image, $filePath, 90);
                break;
            case 'png':
                imagepng($image, $filePath, 9);
                break;
            case 'gif':
                imagegif($image, $filePath);
                break;
            default:
                throw new \RuntimeException('Unsupported image type.');
        }
    }
}
