<?php
class Task
{
    private string $title;
    private string $description;
    private bool $completed;
    private string $filePath;
    private string $fileName;
    private string $originalFileName;

    public function __construct(string $title, string $description, bool $completed, string $filePath, string $fileName, string $originalFileName = '')
    {
        $this->title = $title;
        $this->description = $description;
        $this->completed = $completed;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->originalFileName = $originalFileName;
    }

    public function markCompleted()
    {
        $this->completed = true;
    }

    public function markUncompleted()
    {
        $this->completed = false;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $value)
    {
        $this->title = $value;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $value)
    {
        $this->description = $value;
    }

    public function getCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $value)
    {
        $this->completed = $value;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function setFilePath(string $value)
    {
        $this->filePath = $value;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $value)
    {
        $this->fileName = $value;
    }

    public function getOriginalFileName(): string
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName(string $value)
    {
        $this->originalFileName = $value;
    }

    public function __tostring(): string
    {
        return "({$this->title}, {$this->description}, {$this->completed}, {$this->filePath}, {$this->fileName}, {$this->originalFileName})";
    }
}