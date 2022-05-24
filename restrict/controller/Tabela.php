<?php
class Tabela
{
  private $message = "";
  private $error = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    try {
      Transaction::get();
      $cardapio = new Crud("cardapio");
      $resultado = $cardapio->select();
      $tabela = new Template("view/tabela.html");
      if (is_array($resultado)) {
        $tabela->set("linha", $resultado);
        $this->message = $tabela->saida();
      } else {
        $this->message = $cardapio->getMessage();
        $this->error = $cardapio->getError();
      }
    } catch (Exception $e) {
      $this->message = $e->getMessage();
      $this->error = true;
    }
  }
  public function remover()
  {
    if (isset($_GET["id"])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET["id"]);
        $cardapio = new Crud("cardapio");
        $cardapio->delete("id = $id");
        $this->message = $cardapio->getMessage();
        $this->error = $cardapio->getError();
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    } else {
      $this->message = "Faltando parâmetro!";
      $this->error = true;
    }
  }
  public function getMessage()
  {
    return $this->message;
  }
  public function __destruct()
  {
    Transaction::close();
  }
}