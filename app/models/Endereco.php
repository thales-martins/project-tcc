<?php

class Endereco extends \HXPHP\System\Model
{
    static $table_name = 'enderecos';

    static $validates_presence_of = array(
        array(
            'logradouro',
            'message' => 'O logradouro é um campo obrigatório.'
        ),
        array(
            'numero',
            'message' => 'O número da casa é um campo obrigatório.'
        ),
        array(
            'complemento',
            'message' => 'O complemento é um campo obrigatório.'
        ),
        array(
            'cep',
            'message' => 'O CEP é um campo obrigatório.'
        ),
        array(
            'bairro',
            'message' => 'O bairro é um campo obrigatório.'
        ),
        array(
            'id_cidade',
            'message' => 'A cidade é um campo obrigatório.'
        )
    );

    public static function cadastrar(array $post)
    {
        $resposta = new \stdClass;
        $resposta->endereco = null;
        $resposta->status = false;
        $resposta->errors = array();

        $endereco = self::create($post);

        if ($endereco->is_valid()) {
            $resposta->endereco = $endereco;
            $resposta->status = true;
            return $resposta;
        }

        $errors = $endereco->errors->get_raw_errors();

        foreach ($errors as $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }

    public static function editar($idPessoa, array $atributos)
    {
        $resposta = new \stdClass;
        $resposta->endereco = null;
        $resposta->status = false;
        $resposta->errors = array();

        if(in_array('', $atributos)) {
            array_push($resposta->errors, 'Todos os campos são obrigatórios.');

            return $resposta;
        }

        $endereco = self::find_by_id_pessoa($idPessoa);
        $endereco->logradouro = $atributos['logradouro'];
        $endereco->numero = $atributos['numero'];
        $endereco->complemento = $atributos['complemento'];
        $endereco->cep = $atributos['cep'];
        $endereco->bairro = $atributos['bairro'];
        $endereco->id_cidade = $atributos['id_cidade'];

        if(!empty($resposta->errors)) {
            return $resposta;
        }

        if($endereco->save(false)){
            $resposta->endereco = $endereco;
            $resposta->status = true;

            return $resposta;
        }

        $errors = $endereco->errors->get_raw_errors();

        foreach ($errors as $message) {
            array_push($resposta->errors, $message[0]);
        }

        return $resposta;
    }
}