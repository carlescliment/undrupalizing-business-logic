<?php

namespace carlescliment\Components\DataBase\Adapter\Drupal;

class Query
{
    private $originalSentence;
    private $originalParameters = array();

    private $sanitizedSentence;
    private $sanitizedParameters = array();

    public function __construct($sentence, array $parameters)
    {
        $this->originalSentence = $sentence;
        $this->originalParameters = $parameters;
    }

    public function executeWith(DBQueryWrapper $db_query)
    {
        $this->sanitize();
        $db_query->execute($this->sanitizedSentence, $this->sanitizedParameters);

        return $this;
    }

    private function sanitize()
    {
        $this->sanitizeTables()
            ->sanitizeParameters();

        return $this;
    }

    private function sanitizeTables()
    {
        $this->sanitizedSentence = preg_replace('/`(.*?)`/', '{$1}', $this->originalSentence);

        return $this;
    }

    private function sanitizeParameters()
    {
        $this->sanitizedParameters = array();
        $prepared_parameters = $this->prepareParameters($this->originalParameters);

        foreach ($prepared_parameters as $param) {
            $this->sanitizedSentence = preg_replace('/' . $param['token'] . '/', $this->getPlaceholderFor($param['value']), $this->sanitizedSentence, 1);
            if (!is_null($param['value'])) {
                $this->sanitizedParameters[] = $param['value'];
            }
        }

        return $this;
    }

    private function prepareParameters(array $parameters)
    {
        $prepared_parameters = array();
        $tokens = array_keys($parameters);

        foreach ($tokens as $token)
        {
            $position = 0;
            $count = substr_count($this->originalSentence, $token);
            for ($i = 0; $i < $count; $i++)
            {
                $position = strpos($this->originalSentence, $token, $position + 1);
                if ($position !== false)
                {
                    $prepared_parameters[$position] = array(
                        'token' => $token,
                        'value' => $parameters[$token]
                    );
                }
            }
        }

        ksort($prepared_parameters);

        return $prepared_parameters;
    }

    private function getPlaceholderFor($value)
    {
        if (is_null($value)) {
            return 'null';
        }
        if (is_float($value)) {
            return '%f';
        }
        if (is_integer($value)) {
            return '%d';
        }
        return '"%s"';
    }

}
