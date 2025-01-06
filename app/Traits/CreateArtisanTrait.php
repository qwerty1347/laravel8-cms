<?php

namespace App\Traits;

/**
 * $this->files 은 부모 Class에서 Filesystem을 받아야 함.
 *
 * Trait CreateArtisanTrait
 * @package App\Traits
 */
trait CreateArtisanTrait
{
    /**
     * 생성할 수 있는 디렉토리명
     *
     * @var string[]
     */
    private $directoryAllowed = ['Constants', 'Services', 'Repositories', 'Traits'];

    /**
     * @var string
     */
    private $sampleClassPrefix = 'Sample';

    /**
     * @var
     */
    private $suffix;

    /**
     * @var
     */
    private $directory;

    /**
     * @var
     */
    private $fileName;

    /**
     * @param string $directory
     * @param string $fileName
     * @return bool
     */
    public function create(string $directory, string $fileName)
    {
        if (empty($fileName)) {
            $this->error('만드실 파일명을 입력해 주세요.');
            return false;
        }
        if (empty($directory)) {
            $this->error('만드실 디렉토리를 입력해 주세요.');
            return false;
        }
        $this->directory = ucfirst($directory);
        $this->fileName = $fileName;

        switch ($directory) {
            case 'Constants':
                $this->suffix = "Constant";
                break;

            case 'Services':
                $this->suffix = "Service";
                break;

            case 'Repositories':
                $this->suffix = "Repository";
                break;

            default:
                $this->error('만드실 접미사를 입력해 주세요.');
                return false;
                break;
        }
        $this->createFile();
    }

    /**
     * @return bool
     */
    private function createFile()
    {
        $makeFile = app_path($this->directory . '/' . $this->fileName . '.php');
        if ($this->files->exists($makeFile)) {
            $this->error('해당 파일은 이미 존재합니다.');
            return false;
        }

        $sampleClass = str_replace($this->sampleClassPrefix . $this->suffix, $this->fileName, $this->getSampleClass());
        $this->files->put($makeFile, $sampleClass);
        $this->info($this->directory . ' 생성 성공');
        return true;
    }

    /**
     * @return mixed
     */
    private function getSampleClass()
    {
        return $this->files->get(
            app_path($this->directory . '/' . $this->sampleClassPrefix . $this->suffix . '.php')
        );
    }
}
