<?php

class UploadController extends  Controller
{
    public function uploadFileAction()
    {
        $response = array();
        $file = $this->request->getFile('file');
        if ($file) {
            $fileName = basename($file['name']);
            // if file size more than 700 kb
            if ($file['size'] > 716800) {
                $response['message'] = $this->trans('files larger than %Size% are not allowed to upload.', array('%Size%' => '700 KB'));
            } else if (in_array($file['type'], array('image/png', 'image/jpeg', 'image/gif'))) {
                // if non-english characters detected
                if (!mb_check_encoding($fileName, "ASCII")) {
                    $fileName = 'image_' . time() . '.' . preg_replace('/image\//', '', $file['type']);
                }
                try {
                    $uploadFile = $this->getUploadDir() . $this->getAvailableFileName($fileName);
                    move_uploaded_file($file['tmp_name'], $uploadFile);
                    $response = array(
                        'fileUrl' => BASE_URL . '/uploads/' . basename($uploadFile),
                        'fileName' => basename($uploadFile),
                    );
                } catch (\Exception $ex) {
                    $response['message'] = $this->trans($this->beautifyError($ex->getMessage()));
                }
            } else {
                $response = array(
                    'message' => $this->trans('"%file%" - Image format is not supported', array('%file%' => $fileName)),
                );
            }
        } else {
            $response['message'] = $this->trans('Internal Server Error.');
        }
        echo json_encode($response);
        exit();
    }

    private function getUploadDir()
    {
        return __DIR__ . '/../uploads/';
    }

    private function beautifyError($message)
    {
        return preg_replace('|\w+:[^:]+:\s*(.*) in .*|u', '$1', $message);
    }

    private function getAvailableFileName($file)
    {
        $pathInfo = pathinfo($file);
        if (empty($pathInfo['extension'])) {
            $pathInfo['extension'] = '';
        } else {
            $pathInfo['extension'] = '.' . strtolower($pathInfo['extension']);
        }
        $i = 1;
        $uploadDir = $this->getUploadDir();
        if (!file_exists($this->getUploadDir() . DIRECTORY_SEPARATOR . $file)) {
            return $pathInfo['filename'] . $pathInfo['extension'];
        }
        while (file_exists($uploadDir . DIRECTORY_SEPARATOR . $pathInfo['filename'] . '_' . $i . $pathInfo['extension'])) {
            $i++;
        };

        return $pathInfo['filename'] . '_' . $i . $pathInfo['extension'];
    }
}
