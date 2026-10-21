<?php

namespace App\Controllers;

class Help extends BaseController
{
    public function index(): string
    {
        $faqs = [
            [
                'category' => 'Tecnologia & IA',
                'question' => 'Como a Inteligência Artificial funciona nos instrumentos GuitAIr?',
                'answer'   => 'Nossos instrumentos integram processadores DSP neurais com sensores piezoelétricos e microfones embutidos. O chip analisa a vibração de cada corda em menos de 1.2 milissegundos, permitindo afinação em tempo real, detecção precisa de acordes, guia luminoso no braço com micro-LEDs e sintetização de áudio sem depender de equipamentos externos.'
            ],
            [
                'category' => 'Tecnologia & IA',
                'question' => 'Preciso de internet para tocar ou usar os recursos inteligentes?',
                'answer'   => 'Não! Todo o processamento de áudio, afinação automática e banco de timbres funcionam 100% offline direto no hardware do instrumento. A internet é necessária apenas para baixar novas tablaturas, atualizações de firmware e compartilhar suas gravações na comunidade.'
            ],
            [
                'category' => 'Conectividade & Bateria',
                'question' => 'Como funciona a conexão com smartphones, tablets e computadores?',
                'answer'   => 'O GuitAIr possui Bluetooth 5.3 de ultra-baixa latência para sincronização com o aplicativo GuitAIr (iOS e Android), além de porta USB-C com interface de áudio 24-bit/96kHz nativa, compatível plug-and-play com Ableton, Logic Pro, Reaper, FL Studio e GarageBand.'
            ],
            [
                'category' => 'Conectividade & Bateria',
                'question' => 'Qual é a autonomia da bateria e como recarregar?',
                'answer'   => 'A bateria de lítio de alta densidade dura até 40 horas de uso contínuo no modelo acústico e 25 horas nos modelos elétricos. O carregamento é realizado via cabo USB-C (incluso) e atinge 80% de carga em apenas 45 minutos.'
            ],
            [
                'category' => 'Envio & Garantia',
                'question' => 'Qual é o prazo de entrega e como acompanho meu pedido?',
                'answer'   => 'O envio é realizado com seguro total e embalagem reforçada anti-impacto. O prazo médio para as capitais é de 3 a 5 dias úteis e de 5 a 10 dias úteis para o restante do Brasil. Assim que o pedido é despachado, você recebe o código de rastreio por e-mail e pode acompanhar na sua área Minha Conta.'
            ],
            [
                'category' => 'Envio & Garantia',
                'question' => 'Existe garantia e política de devolução?',
                'answer'   => 'Oferecemos 2 anos de garantia total contra qualquer defeito de fabricação ou dos componentes eletrônicos. Além disso, você tem 30 dias de teste sem compromisso: se não amar o GuitAIr, devolvemos 100% do seu dinheiro sem burocracia.'
            ],
        ];

        return view('help', [
            'faqs'      => $faqs,
            'pageTitle' => 'GuitAIr - Central de Ajuda & Dúvidas Frequentes'
        ]);
    }
}
