<?php
namespace app\components\VueWigdets;

use yii\widgets\InputWidget;
use yii\base\InvalidConfigException;
use yii\base\InvalidArgumentException;

class VueTextInputWidget extends InputWidget
{
    const TEXT_INPUT = "Input";
    const TEXT_AREA = "Textarea";
    const DROP_DOWN_LIST = "DropDownList";
    const MULTI_SELECT_CHECKBOX = "MultiSelectCheckbox";

    public $type;
    public $items;
    public $checkBoxAttributes;

    public function init()
    {
        parent::init();

        if ($this->type == null) {
            $this->type = self::TEXT_INPUT;
        }

        if ($this->type == self::DROP_DOWN_LIST && !is_array($this->items)) {
            throw new InvalidConfigException("Property `items`. Must be an array");
        }

        if ($this->type == self::MULTI_SELECT_CHECKBOX && !is_string($this->items)) {
            throw new InvalidConfigException("Property `items`. Must be string (Vue data.`list_name`)");
        }
    }

    public function run()
    {
        parent::run();

        switch($this->type) {
            case self::TEXT_INPUT:
                return $this->render('text-input', [
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'options' => $this->options
                ]);
            case self::TEXT_AREA:
                return $this->render('text-area', [
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'options' => $this->options
                ]);
            case self::DROP_DOWN_LIST:
                return $this->render('dropdown-input', [
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'options' => $this->options,
                    'items' => $this->items
                ]);
            case self::MULTI_SELECT_CHECKBOX:
                return $this->render('multi-select-checkbox', [
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'options' => $this->options,
                    'items' => $this->items,
                    'checkBoxAttributes' => $this->checkBoxAttributes,
                ]);
            default:
                throw new InvalidArgumentException("Field type not supported");
        }
    }
}
