<?php

namespace aeqdev\afw\APDO\Schema\Column;

use aeqdev\afw\APDO\Schema\ColumnFormField;
use aeqdev\afw\APDO\Schema\Row;
use aeqdev\afw\APDO\Schema\Table;
use aeqdev\afw\controller\Form\Element;
use aeqdev\APDO\Schema\ColumnSkipException;

class String extends \aeqdev\APDO\Schema\Column\String
{
    use ColumnFormField;

    public static $filesDir = './files/';
    public static $filesUri = '/files/';

    public function __construct()
    {
        parent::__construct();
        $this->formFieldText();
    }

    public function getFilePath(Row $row)
    {
        $path = $row->table->name . '/' . $this->name;
        foreach ((array)$row->pkey() as $pkeyPart) {
            $path .= '/' . implode('/', str_split($pkeyPart, 2));
        }
        return $path . '.' . $row->{$this->name};
    }

    public function getFileUri(Row $row)
    {
        return self::$filesUri . $this->getFilePath($row);
    }

    /**
     * @param callable $putCallback
     * @param callable $deleteCallback
     * @param string $error_message
     * @param string $defaultValue
     */
    public function upload($putCallback, $deleteCallback, $error_message = null, $defaultValue = null)
    {
        $manager = new String_FileManager($this, $putCallback, $deleteCallback, $error_message);

        $before = function(Row $row) use ($manager) {
            if (!$row->isNew()) {
                if (!empty($row->{$this->name})) {
                    $manager->toDelete = $this->getFilePath($row);
                }
            }
        };

        $after = function(Row $row) use ($manager, $error_message) {
            if (!$manager->exec($this->getFilePath($row))) {
                throw new \Exception($error_message);
            }
        };

        $this->table->addEventListener(Table::EVENT_BEFORE_SAVE, $before);
        $this->table->addEventListener(Table::EVENT_BEFORE_DELETE, $before);
        $this->table->addEventListener(Table::EVENT_SAVE, $after);
        $this->table->addEventListener(Table::EVENT_DELETE, $after);

        $this->addValidator(function($value, $row, $column) use ($manager, $defaultValue) {
            if (is_array($value)) {
                if (empty($value['tmp_file'])) {
                    throw new ColumnSkipException($row, $column);
                }
                $manager->toSave = $value['tmp_file'];
                $basename = $value['name'];
            } else {
                $manager->toSave = $value;
                $basename = basename($value);
            }
            $p = strrpos($basename, '.');
            if ($p === false) {
                $value = isset($defaultValue) ? $defaultValue : 'txt';
            } else {
                $value = substr($basename, $p + 1);
            }

            return $value;
        });
    }

    /**
     * @param string $error_message
     * @param string $defaultValue
     * @param callable $putCallback
     * @param callable $deleteCallback
     * @param string $baseDir
     */
    public function file($error_message = null, $defaultValue = null,
                         $putCallback = null, $deleteCallback = null,
                         $baseDir = null)
    {
        if (!isset($baseDir)) {
            $baseDir = self::$filesDir;
        }

        if (!isset($putCallback)) {
            $putCallback = function($src, $dest) {
                if (is_uploaded_file($src)) {
                    return move_uploaded_file($src, $dest);
                } else {
                    return rename($src, $dest);
                }
            };
        }

        if (!isset($deleteCallback)) {
            $deleteCallback = function($src) {
                return unlink($src);
            };
        }

        $this->upload(
            function($src, $dest) use ($putCallback, $baseDir) {
                $dest = $baseDir . $dest;
                $dir = dirname($dest);
                if (!file_exists($dir)) {
                    mkdir($dir, null, true);
                }
                return $putCallback($src, $dest);
            },
            function($path) use ($deleteCallback, $baseDir) {
                return $deleteCallback($baseDir . $path);
            },
            $error_message,
            $defaultValue
        );
    }

    /**
     * @param string $baseDir
     * @param string $imagemagickCmdOptions
     * @param string $error_message
     * @param string $defaultValue
     */
    public function imagemagick($baseDir, $imagemagickCmdOptions, $error_message = null, $defaultValue = 'jpg')
    {
        $this->file($baseDir, $error_message, $defaultValue, function($src, $dest) use ($imagemagickCmdOptions) {
            $r = trim(shell_exec(
                escapeshellcmd("convert $imagemagickCmdOptions ")
                . escapeshellarg($src)
                . ' '
                . escapeshellarg($dest)
                . ' 2>&1'
            ));
            return empty($r);
        });
    }

    public function formFieldColor()
    {
        $this->formFieldCreator(function() {
            return Element::color($this->comment, $this->name);
        });
    }

    public function formFieldTel()
    {
        $this->formFieldCreator(function() {
            return Element::tel($this->comment, $this->name);
        });
    }

    public function formFieldUrl()
    {
        $this->formFieldCreator(function() {
            return Element::url($this->comment, $this->name);
        });
    }

    public function formFieldPassword()
    {
        $this->formFieldCreator(function() {
            return Element::password($this->comment, $this->name);
        });
    }

    public function formFieldSelect($options = null, $padding = null)
    {
        $this->formFieldCreator(function() use ($options, $padding) {
            return Element::select($this->comment, $options, $this->name, $padding);
        });
    }

    public function formFieldFile($labelDelete = null, $maxFileSize = null)
    {
        $this->formFieldCreator(function() use ($labelDelete, $maxFileSize) {
            return Element::file($this->comment, $this->name);
        });
    }

    public function formFieldImage($labelDelete = null, $maxFileSize = null)
    {
        $this->formFieldCreator(function() use ($labelDelete, $maxFileSize) {
            return Element::image($this->comment, $this->name);
        });
    }

}

class String_FileManager
{

    public $toDelete;
    public $toSave;

    /**
     * @var callable
     */
    private $put;

    /**
     * @var callable
     */
    private $delete;

    public function __construct($putCallback, $deleteCallback)
    {
        $this->put = $putCallback;
        $this->delete = $deleteCallback;
    }

    public function exec($dest)
    {
        $done = true;

        if (isset($this->toSave)) {
            $done = call_user_func($this->put, $this->toSave, $dest);
        }

        if ($done && isset($this->toDelete)) {
            $done = call_user_func($this->delete, $this->toDelete);
        }

        $this->toSave = null;
        $this->toDelete = null;

        return $done;
    }

}
