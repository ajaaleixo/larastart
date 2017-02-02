<?php
/** Larastart
 *
 * (The MIT license)
 * Copyright (c) 2017 andrealeixo.com
 */

namespace Larastart\Template\Migration;

use Larastart\Resource\Model\Column;
use Larastart\Resource\Model\ModelInterface;
use Larastart\Template\TemplateAbstract;
use Carbon\Carbon;

class MigrationTemplate extends TemplateAbstract
{
    protected $model = null;
    protected $defaultTemplatePath = __DIR__.DIRECTORY_SEPARATOR."Migration.php.template";
    protected $defaultStoragePath  = DIRECTORY_SEPARATOR."database".DIRECTORY_SEPARATOR."migrations";

    public function __construct(ModelInterface $model, string $storagePath, string $templatePath = null)
    {
        $this->model           = $model;
        $this->templatePath    = $templatePath ?: $this->defaultTemplatePath;
        if (realpath($storagePath) === false) {
            $this->storagePath = getcwd().DIRECTORY_SEPARATOR.$storagePath.$this->defaultStoragePath;
        } else {
            $this->storagePath = realpath($storagePath).$this->defaultStoragePath;
        }
        $this->storageFileName = $this->makeFileName($model);
    }

    public function render(string $contents = ''):string
    {
        $contents     = $contents ?: $this->loadTemplate();
        $replacePairs = array(
            '!!className!!'       => $this->getMigrationClassName($this->model),
            '!!tableName!!'       => $this->getTableName($this->model),
            '!!columns!!'         => $this->getColumns($this->model),
        );
        return strtr($contents, $replacePairs);
    }

    protected function makeFileName(ModelInterface $model)
    {
        $time = Carbon::now();
        $str = "%s_%s_%s_%s%s%s_%s";
        return sprintf($str,
            $time->year,
            str_pad($time->month, 2, "0", STR_PAD_LEFT),
            str_pad($time->day, 2, "0", STR_PAD_LEFT),
            str_pad($time->hour, 2, "0", STR_PAD_LEFT),
            str_pad($time->minute, 2, "0", STR_PAD_LEFT),
            str_pad($time->second, 2, "0", STR_PAD_LEFT),
            "create_".strtolower($model->getName())."_table.php"
        );
    }

    protected function getColumns(ModelInterface $model)
    {
        $output = [];
        foreach($model->getColumns() as $column) {
            /* @var Column $column */
            switch($column->getType()) {
                case "increments":
                    $output[]= $this->getIncrements($column);
                    break;
                case "integer":
                    $output[]= $this->getInteger($column);
                    break;
                case "smallInteger":
                    $output[]= $this->getSmallInteger($column);
                    break;
                case "string":
                    $output[]= $this->getString($column);
                    break;
                case "text":
                    $output[]= $this->getText($column);
                    break;
                case "longText":
                    $output[]= $this->getLongText($column);
                    break;
                case "ipAddress":
                    $output[]= $this->getIpAddress($column);
                    break;
            }
        }

        // Columns to Append
        if ($this->model->useSoftDeletes()) {
            $output[]= $this->getSoftDeletes();
        }

        // Timestamps
        if ($this->model->useTimestamps()) {
            $output[]= $this->getTimestamps();
        }

        // Indexes
        foreach($model->getColumns() as $column) {
            /* @var Column $column */
            if($column->index()) {
                $output[]= $this->getIndex($column);
            }
        }


        return implode("\n\t\t\t", $output);
    }

    protected function getTableName(ModelInterface $model):string
    {
        return strtolower($model->getTable());
    }

    protected function getMigrationClassName(ModelInterface $model):string
    {
        return "Create".$model->getName()."Table";
    }

    protected function getIncrements(Column $col)
    {
        return '$table->increments("'.$col->getName().'")'.';';
    }

    protected function getInteger(Column $col)
    {
        return '$table->integer("'.$col->getName().'")'.
            $this->getUnique($col).
            $this->getNullable($col).
            $this->getUnsigned($col).
            $this->getDefault($col).
            ';';
    }

    protected function getIpAddress(Column $col)
    {
        return '$table->ipAddress("'.$col->getName().'")'.
            $this->getUnique($col).
            $this->getNullable($col).
            $this->getDefault($col).
            ';';
    }

    protected function getString(Column $col)
    {
        $length = $col->getLength() ? ", ".$col->getLength() : "";
        return '$table->string("'.$col->getName().'"'.$length.')'.
            $this->getUnique($col).
            $this->getNullable($col).
            $this->getDefault($col).
            ';';
    }

    protected function getRememberToken()
    {
        return '$table->rememberToken();';
    }

    protected function getTimestamps()
    {
        return '$table->timestamps();';
    }

    protected function getSoftDeletes()
    {
        return '$table->softDeletes();';
    }

    protected function getText(Column $col)
    {
        return '$table->text("'.$col->getName().'")'.
            $this->getUnique($col).
            $this->getNullable($col).
            $this->getDefault($col).
            ';';
    }

    protected function getLongText(Column $col)
    {
        return '$table->longText("'.$col->getName().'")'.
            $this->getUnique($col).
            $this->getNullable($col).
            $this->getDefault($col).
            ';';
    }

    protected function getSmallInteger(Column $col)
    {
        return '$table->smallInteger("'.$col->getName().'")'.
            $this->getUnique($col).
            $this->getNullable($col).
            $this->getUnsigned($col).
            $this->getDefault($col).
            ';';
    }

    protected function getDefault(Column $col)
    {
        $defaultValue = $col->getDefault();
        if ($defaultValue) {
            return "->default(".(is_string($defaultValue) ? "'".$defaultValue."'" : $defaultValue).")";
        }
    }

    protected function getUnique(Column $col)
    {
        if ($col->isUnique()) {
            return "->unique()";
        }
        return "";
    }

    protected function getNullable(Column $col)
    {
        if ($col->isNullable()) {
            return "->nullable()";
        }
        return "";
    }

    protected function getUnsigned(Column $col)
    {
        if ($col->isUnsigned()) {
            return "->unsigned()";
        }
        return "";
    }

    protected function getIndex(Column $col)
    {
        $str = '$table->index(%s);';
        $theIndex = $col->index();
        if (!empty($theIndex)) {
            switch (true) {
                case is_array($theIndex):
                    // Compound index of two columns
                    return sprintf($str, "['".implode("', '", $theIndex)."']");
                case is_string($theIndex):
                    // Column name would be indexed, index name would be the defined string
                    return sprintf($str, "'".$col->getName()."', '".$theIndex."'");
                case is_bool($theIndex):
                    return sprintf($str, "'".$col->getName()."'");
            }
        }
        return "";
    }
}
