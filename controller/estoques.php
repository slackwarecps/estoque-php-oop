<?php

/**
 * Description of estroques
 *
 * @author fabio
 */
class estoques extends controllerBasico {

    // index do Controller
    public function index() {
        $acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : null;
        if ($acao == 'novo') {
            $this->novo();
        } elseif ($acao == 'alterar') {
            $this->geraFormAlterar($_REQUEST);
        } elseif ($acao == 'excluir') {
            $this->geraFormExcluir($_REQUEST);
        } elseif ($acao == 'excluirdefinitivo') {
            $this->remover($_REQUEST);
        }elseif ($acao == 'erro') {
             $pagina = 1;

            $html_grid = $this->geraGrid();
            $html_frm_novo = $this->geraFormNovo();
            //die('opa');

            $this->smarty->assign("grid", $html_grid);
            $this->smarty->assign("erro", $_SESSION['erro_msg']);
            $this->smarty->assign("frm_novo", $html_frm_novo);
            $this->smarty->assign("paginador", $this->paginador($pagina, 100));
            $this->smarty->display('estoques/index.tpl');
            
        }elseif ($acao == 'atualizar') {
            $this->atualizar($_REQUEST);
        } elseif ($acao == 'salvar') {
            $this->salvar($_POST);
        } elseif ($acao == null) {
            $pagina = 1;

            $html_grid = $this->geraGrid();
            $html_frm_novo = $this->geraFormNovo();
          
            $this->smarty->assign("erro", "");
            $this->smarty->assign("grid", $html_grid);
            $this->smarty->assign("frm_novo", $html_frm_novo);
            $this->smarty->assign("paginador", $this->paginador($pagina, 100));
            $this->smarty->display('estoques/index.tpl');
        }
    }

    /**
     * Funcao de Adicionar Estoques
     */
    public function geraFormNovo() {
        return $this->smarty->fetch('estoques/novo.tpl');
    }

    /**
     * Funcao de Adicionar Estoques
     */
    public function geraFormAlterar($request) {
        //var_dump($request);

        $model = new modelEstoques();
        $registro = $model->getEstoquesById($request['id']);

        // var_dump($registro);

        $this->smarty->assign("dados", $registro);
        $this->smarty->display('estoques/alterar.tpl');
    }

    /**
     * Funcao de Adicionar Estoques
     */
    public function geraFormExcluir($request) {
        //var_dump($request);

        $model = new modelEstoques();
        $registro = $model->getEstoquesById($request['id']);



        $this->smarty->assign("dados", $registro);
        $this->smarty->display('estoques/excluir.tpl');
    }

    /**
     * Funcao de Adicionar Estoques
     */
    public function salvar($postlocal) {
        //valida registro
        $okvalidacao = $this->validaRegistro($postlocal);

        if ($okvalidacao) {
            $model = new modelEstoques();
            $model->setEstoques($postlocal);
            header('Location: cad_estoque.php');
        }
    }

    public function atualizar($postlocal) {
        $model = new modelEstoques();
        $model->updateEstoques($postlocal);
        header('Location: cad_estoque.php');
    }

    public function remover($postlocal) {
        $model = new modelEstoques();
        $model->deleteEstoques($postlocal);
        header('Location: cad_estoque.php');
    }

    /**
     * Funcao de Adicionar Estoques
     */
    public function editar() {

        $this->smarty->assign("msg", "editado com sucesso");

        $this->smarty->display('estoques/index.tpl');
    }

    /**
     * 
     * @param type $dados
     * @return type
     */
    public function geraGrid() {

        $myModel = new modelEstoques();

        $dados = $myModel->listaCompleta();
        $this->smarty->assign('data', $dados);
        $this->smarty->assign('tr', array('bgcolor="#eeeeee"', 'bgcolor="#dddddd"'));
        return $this->smarty->fetch('estoques/gridpadrao.tpl');
    }

    /**
     * 
     * @param type $dados
     * @return type
     */
    public function validaRegistro($registro) {
        $ok = true;
        $msg_erro="";
        
        if ($registro['descricao']==="") {$msg_erro.="O Campo Descrição é obrigatorio! ";} 
        
        if($msg_erro!="") {
            $ok = false;
            $_SESSION['erro_msg'] = $msg_erro. " Verifique os dados.";
            header('Location: cad_estoque.php?acao=erro');
        }
        return $ok;
    }

}