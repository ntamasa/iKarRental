<?php
include_once "jsonio.php";

class JsonStorage
{
    private $io;
    private $filename;
    public function __construct(string $filename)
    {
        $this->io = new JsonIO();
        $this->filename = $filename;
    }
    public function all()
    {
        return (array) $this->io->load_from_file($this->filename, true);
    }
    public function insert(object $item): string
    {
        $all = $this->all();
        $id = uniqid('', true);
        $item->_id = $id;
        $all[$id] = $item;
        $this->io->save_to_file($this->filename, $all);
        return $id;
    }
    public function filter(callable $fn)
    {
        $all = $this->all();
        return array_filter($all, $fn);
    }
}
