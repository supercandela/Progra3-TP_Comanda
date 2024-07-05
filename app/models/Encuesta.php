<?php

class Encuesta
{
    public $id;
    public $id_mesa; 
    public $id_pedido;
    public $nota_restaurante;
    public $nota_mesa;
    public $nota_mozo;
    public $nota_cocinero;
    public $comentarios;


    public static function traerEncuestaPorMesaYPedido ($mesa, $pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas WHERE id_mesa = :mesa AND id_pedido = :id_pedido;");
        $consulta->bindValue(':mesa', $mesa, PDO::PARAM_INT);
        $consulta->bindValue(':id_pedido', $pedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public function completarEncuesta ()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "UPDATE encuestas SET nota_restaurante = :nota_restaurante, nota_mesa = :nota_mesa, nota_mozo = :nota_mozo, nota_cocinero = :nota_cocinero, comentarios = :comentarios WHERE id = :id;"
        );
        
        $consulta->bindValue(':nota_restaurante', $this->nota_restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':nota_mesa', $this->nota_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':nota_mozo', $this->nota_mozo, PDO::PARAM_INT);
        $consulta->bindValue(':nota_cocinero', $this->nota_cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':comentarios', $this->comentarios, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
