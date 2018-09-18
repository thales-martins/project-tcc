<?php

class InicioController extends \HXPHP\System\Controller
{
    public function __construct(\HXPHP\System\Configs\Config $configs = null)
    {
        parent::__construct($configs);

        $this->load(
            'Services\Auth',
            $configs->auth->after_login,
            $configs->auth->after_logout,
            true
        );

        $this->auth->redirectCheck(false);

        $this->auth->roleCheck(array('C', 'O'));

        $this->load('Helpers\Menu',
            $this->request,
            $this->configs,
            $this->auth->getUserRole()
        );
    }

    public function indexAction()
    {
        if($this->auth->getUserRole() == 'O') {
            $this->verificarPrazo();
        }
    }

    private function verificarPrazo()
    {
        $diligencias = Diligencia::all(array(
            'conditions' => array('status = ?', 'A')
        ));

        foreach ($diligencias as $diligencia) {
            $tipoDiligenciaUrgente = TipoDiligencia::find_by_tipo('Urgente');

            if($diligencia->id_tipo_diligencia != $tipoDiligenciaUrgente->id) {
                $evento = Evento::find_by_id_diligencia($diligencia->id, array(
                    'order' => 'data desc'
                ));

                if ($evento->id_autor == $this->auth->getUserId()) {
                    if(empty(Notificacao::find_by_id_diligencia($diligencia->id))) {

                        $dataAtual = new DateTime(date('Y-m-d'));
                        $prazoCumprimento = new DateTime($diligencia->prazo_cumprimento->format('Y-m-d'));

                        $intervalo = $dataAtual->diff($prazoCumprimento);

                        if ($intervalo->d == 1) {
                            $notificacao = array(
                                'id_diligencia' => $diligencia->id,
                                'id_destinatario' => $this->auth->getUserId(),
                                'mensagem' => 'O prazo de uma diligência está se esgotando, clique para visualizá-la.',
                                'data' => date('Y-m-d H:i:s')
                            );

                            Notificacao::cadastrar($notificacao);
                        } else if ($intervalo->invert == 1) {
                            $notificacao = array(
                                'id_diligencia' => $diligencia->id,
                                'id_destinatario' => $this->auth->getUserId(),
                                'mensagem' => 'Uma diligência não foi cumprida dentro do prazo, clique para visualizá-la.',
                                'data' => date('Y-m-d H:i:s')
                            );

                            Notificacao::cadastrar($notificacao);
                        }
                    }
                }
            }
        }
    }
}