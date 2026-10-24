<?php

if (!function_exists('pix_svg')) {
    /**
     * Retorna o SVG oficial do PIX conforme especificação do sistema
     */
    function pix_svg($size = 16, $style = '')
    {
        $styleAttr = !empty($style) ? ' style="' . esc($style, 'attr') . '"' : '';
        return '<svg fill="currentColor" viewBox="0 0 16 16" width="' . (int)$size . '" height="' . (int)$size . '" xmlns="http://www.w3.org/2000/svg"' . $styleAttr . ' class="pix-svg-icon" aria-hidden="true"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M11.917 11.71a2.046 2.046 0 0 1-1.454-.602l-2.1-2.1a.4.4 0 0 0-.551 0l-2.108 2.108a2.044 2.044 0 0 1-1.454.602h-.414l2.66 2.66c.83.83 2.177.83 3.007 0l2.667-2.668h-.253zM4.25 4.282c.55 0 1.066.214 1.454.602l2.108 2.108a.39.39 0 0 0 .552 0l2.1-2.1a2.044 2.044 0 0 1 1.453-.602h.253L9.503 1.623a2.127 2.127 0 0 0-3.007 0l-2.66 2.66h.414z"></path><path d="m14.377 6.496-1.612-1.612a.307.307 0 0 1-.114.023h-.733c-.379 0-.75.154-1.017.422l-2.1 2.1a1.005 1.005 0 0 1-1.425 0L5.268 5.32a1.448 1.448 0 0 0-1.018-.422h-.9a.306.306 0 0 1-.109-.021L1.623 6.496c-.83.83-.83 2.177 0 3.008l1.618 1.618a.305.305 0 0 1 .108-.022h.901c.38 0 .75-.153 1.018-.421L7.375 8.57a1.034 1.034 0 0 1 1.426 0l2.1 2.1c.267.268.638.421 1.017.421h.733c.04 0 .079.01.114.024l1.612-1.612c.83-.83.83-2.178 0-3.008z"></path></g></svg>';
    }
}

if (!function_exists('whatsapp_svg')) {
    /**
     * Retorna o SVG oficial do WhatsApp conforme especificação do sistema
     */
    function whatsapp_svg($size = 20, $style = '')
    {
        $styleAttr = !empty($style) ? ' style="' . esc($style, 'attr') . '"' : '';
        return '<svg fill="currentColor" viewBox="0 0 32 32" width="' . (int)$size . '" height="' . (int)$size . '" version="1.1" xmlns="http://www.w3.org/2000/svg"' . $styleAttr . ' class="whatsapp-svg-icon" aria-hidden="true"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>whatsapp</title><path d="M26.576 5.363c-2.69-2.69-6.406-4.354-10.511-4.354-8.209 0-14.865 6.655-14.865 14.865 0 2.732 0.737 5.291 2.022 7.491l-0.038-0.070-2.109 7.702 7.879-2.067c2.051 1.139 4.498 1.809 7.102 1.809h0.006c8.209-0.003 14.862-6.659 14.862-14.868 0-4.103-1.662-7.817-4.349-10.507l0 0zM16.062 28.228h-0.005c-0 0-0.001 0-0.001 0-2.319 0-4.489-0.64-6.342-1.753l0.056 0.031-0.451-0.267-4.675 1.227 1.247-4.559-0.294-0.467c-1.185-1.862-1.889-4.131-1.889-6.565 0-6.822 5.531-12.353 12.353-12.353s12.353 5.531 12.353 12.353c0 6.822-5.53 12.353-12.353 12.353h-0zM22.838 18.977c-0.371-0.186-2.197-1.083-2.537-1.208-0.341-0.124-0.589-0.185-0.837 0.187-0.246 0.371-0.958 1.207-1.175 1.455-0.216 0.249-0.434 0.279-0.805 0.094-1.15-0.466-2.138-1.087-2.997-1.852l0.010 0.009c-0.799-0.74-1.484-1.587-2.037-2.521l-0.028-0.052c-0.216-0.371-0.023-0.572 0.162-0.757 0.167-0.166 0.372-0.434 0.557-0.65 0.146-0.179 0.271-0.384 0.366-0.604l0.006-0.017c0.043-0.087 0.068-0.188 0.068-0.296 0-0.131-0.037-0.253-0.101-0.357l0.002 0.003c-0.094-0.186-0.836-2.014-1.145-2.758-0.302-0.724-0.609-0.625-0.836-0.637-0.216-0.010-0.464-0.012-0.712-0.012-0.395 0.010-0.746 0.188-0.988 0.463l-0.001 0.002c-0.802 0.761-1.3 1.834-1.3 3.023 0 0.026 0 0.053 0.001 0.079l-0-0.004c0.131 1.467 0.681 2.784 1.527 3.857l-0.012-0.015c1.604 2.379 3.742 4.282 6.251 5.564l0.094 0.043c0.548 0.248 1.25 0.513 1.968 0.74l0.149 0.041c0.442 0.14 0.951 0.221 1.479 0.221 0.303 0 0.601-0.027 0.889-0.078l-0.031 0.004c1.069-0.223 1.956-0.868 2.497-1.749l0.009-0.017c0.165-0.366 0.261-0.793 0.261-1.242 0-0.185-0.016-0.366-0.047-0.542l0.003 0.019c-0.092-0.155-0.34-0.247-0.712-0.434z"></path></g></svg>';
    }
}

if (!function_exists('whatsapp_phone_number')) {
    function whatsapp_phone_number()
    {
        return '42998072016';
    }
}

if (!function_exists('whatsapp_phone_formatted')) {
    function whatsapp_phone_formatted()
    {
        return '(42) 99807-2016';
    }
}

if (!function_exists('whatsapp_link')) {
    function whatsapp_link($message = '')
    {
        $url = 'https://wa.me/5542998072016';
        if (!empty($message)) {
            $url .= '?text=' . rawurlencode($message);
        }
        return $url;
    }
}

if (!function_exists('payment_method_label')) {
    /**
     * Retorna o nome da forma de pagamento traduzido para Português
     */
    function payment_method_label($method)
    {
        $key = strtolower(trim((string)$method));
        $labels = [
            'credit_card' => 'Cartão de Crédito',
            'cartao'      => 'Cartão de Crédito',
            'card'        => 'Cartão de Crédito',
            'pix'         => 'Pix',
            'boleto'      => 'Boleto Bancário',
        ];

        return $labels[$key] ?? strtoupper($method);
    }
}

if (!function_exists('payment_method_badge')) {
    /**
     * Retorna o badge estilizado em HTML com o texto em Português e ícone correto
     */
    function payment_method_badge($method)
    {
        $key = strtolower(trim((string)$method));
        $label = payment_method_label($key);

        if ($key === 'pix') {
            return '<span class="payment-badge payment-pix">' . pix_svg(14, 'margin-right: 4px;') . ' ' . esc($label) . '</span>';
        }

        if ($key === 'credit_card' || $key === 'cartao' || $key === 'card') {
            return '<span class="payment-badge payment-credit_card"><span class="material-symbols-outlined" style="font-size: 14px; margin-right: 3px;">credit_card</span> ' . esc($label) . '</span>';
        }

        if ($key === 'boleto') {
            return '<span class="payment-badge payment-boleto"><span class="material-symbols-outlined" style="font-size: 14px; margin-right: 3px;">receipt_long</span> ' . esc($label) . '</span>';
        }

        return '<span class="payment-badge payment-' . esc($key, 'attr') . '">' . esc($label) . '</span>';
    }
}

if (!function_exists('order_status_label')) {
    /**
     * Retorna o rótulo do status do pedido em Português
     */
    function order_status_label($status)
    {
        $key = strtolower(trim((string)$status));
        $statuses = [
            'pending'   => 'Pendente',
            'paid'      => 'Pago',
            'shipped'   => 'Enviado',
            'completed' => 'Concluído',
            'cancelled' => 'Cancelado',
        ];

        return $statuses[$key] ?? ucfirst($status);
    }
}

if (!function_exists('order_status_badge')) {
    /**
     * Retorna o badge com status em Português
     */
    function order_status_badge($status)
    {
        $key = strtolower(trim((string)$status));
        $label = order_status_label($key);
        return '<span class="status-badge status-' . esc($key, 'attr') . '">' . esc($label) . '</span>';
    }
}
