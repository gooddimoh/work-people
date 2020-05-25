<?php
namespace app\components;

class VeeValidateHelper
{
    /**
     * @param $model \yii\db\ActiveRecord
     * register js VeeValidate validator
     * @return string validator name
     */
    public static function getVValidateString($view, $model, $attribute)
    {
        $class_name = str_replace('\\', '_', get_class($model));
        $validator_prefix = $class_name .'_' . $attribute;

        $attribute_validator_list = [];

        $validators = $model->getActiveValidators($attribute);
        foreach($validators as $validator) {
            // var_dump($validator->getClientOptions($model, $attribute));
            // $validators = $modelJob->getActiveValidators('company_name');
            // var_dump($validators[0]->getClientOptions($model, $attribute));
            // var_dump($validators[1]);
            $validator_type = str_replace('\\', '_', get_class($validator));
            $validator_name = $validator_prefix . '_' . $validator_type;

            switch($validator_type) {
                case 'yii_validators_RequiredValidator':
                case 'yii_validators_ExistValidator':
                    $options = $validator->getClientOptions($model, $attribute);
                    $message = empty($options['message']) ? '' : $options['message'];

                    $validator_string = <<<JS
                        VeeValidate.Validator.extend('{$validator_name}', {
                            getMessage: function(field) {
                                return '{$message}';
                            },
                            validate: function(value) {
                                if(typeof value == 'string') {
                                    return value !== void 0 && value.trim().length !== 0;
                                }

                                return value !== void 0;
                            }
                        });
JS;
                    // register validator
                    $view->registerJs($validator_string);
                    $attribute_validator_list[] = $validator_name;
                    $attribute_validator_list[] = 'required';
                    break;
                case 'yii_validators_StringValidator':
                    $options = $validator->getClientOptions($model, $attribute);
                    if(!empty($options['min'])) {
                        $message = $options['tooShort'];
                        $min_len = $options['min'];
                        $validator_name = $validator_name. '_min';

                        $validator_string = <<<JS
                        VeeValidate.Validator.extend('{$validator_name}', {
                            getMessage: function(field) {
                                return '{$message}';
                            },
                            validate: function(value) {
                                return value.trim().length >= {$min_len};
                            }
                        });
JS;
                        // register validator
                        $view->registerJs($validator_string);
                        $attribute_validator_list[] = $validator_name;
                    } elseif(!empty($options['max'])) {
                        $message = $options['tooLong'];
                        $max_len = $options['max'];
                        $validator_name = $validator_name. '_max';

                        $validator_string = <<<JS
                        VeeValidate.Validator.extend('{$validator_name}', {
                            getMessage: function(field) {
                                return '{$message}';
                            },
                            validate: function(value) {
                                return value.trim().length < {$max_len};
                            }
                        });
JS;
                        // register validator
                        $view->registerJs($validator_string);
                        $attribute_validator_list[] = $validator_name;
                    } else { // ??
                        // Значение «{attribute}» должно быть строкой
                    }
                    break;
                case 'yii_validators_NumberValidator':
                    $options = $validator->getClientOptions($model, $attribute);
                    $message = $options['message'];
                    $expression = $options['pattern']->expression;

                    $validator_string = <<<JS
                        VeeValidate.Validator.extend('{$validator_name}', {
                            getMessage: function(field) {
                                return '{$message}';
                            },
                            validate: function(value) {
                                return {$expression}.test(value);
                            }
                        });
JS;
                    // register validator
                    $view->registerJs($validator_string);
                    $attribute_validator_list[] = $validator_name;

                    // min number value
                    if(!empty($options['min'])) {
                        $message = $options['tooSmall'];
                        $min_val = $options['min'];
                        $validator_name_min = $validator_name. '_min_num';

                        $validator_string = <<<JS
                        VeeValidate.Validator.extend('{$validator_name_min}', {
                            getMessage: function(field) {
                                return '{$message}';
                            },
                            validate: function(value) {
                                return Number({$min_val}) <= value;
                            }
                        });
JS;
                        // register validator
                        $view->registerJs($validator_string);
                        $attribute_validator_list[] = $validator_name_min;
                    }
                    
                    // max number value
                    if(!empty($options['max'])) {
                        $message = $options['tooBig'];
                        $max_val = $options['max'];
                        $validator_name_max = $validator_name. '_max_num';

                        $validator_string = <<<JS
                        VeeValidate.Validator.extend('{$validator_name_max}', {
                            getMessage: function(field) {
                                return '{$message}';
                            },
                            validate: function(value) {
                                return Number({$max_val}) >= value;
                            }
                        });
JS;
                        // register validator
                        $view->registerJs($validator_string);
                        $attribute_validator_list[] = $validator_name_max;
                    }
                    break;
                case 'yii_validators_DateValidator':
                case 'yii_validators_SafeValidator':
                case 'yii_validators_UniqueValidator':
                case 'yii_validators_DefaultValueValidator':
                    break;
                case 'yii_validators_EmailValidator':
                    $options = $validator->getClientOptions($model, $attribute);
                    $message = $options['message'];
                    $expression = $options['pattern']->expression;

                    $validator_string = <<<JS
                        VeeValidate.Validator.extend('{$validator_name}', {
                            getMessage: function(field) {
                                return '{$message}';
                            },
                            validate: function(value) {
                                return {$expression}.test(value);
                            }
                        });
JS;
                    // register validator
                    $view->registerJs($validator_string);
                    $attribute_validator_list[] = $validator_name;
                    break;
                default:
                    throw new \Exception('Unknown validator type: ' . $validator_type);
            }
        }

        $attribute_validator_list = array_unique($attribute_validator_list); // filter duplicate validator: 'required'

        return implode('|', $attribute_validator_list);
    }
}
