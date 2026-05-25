<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug'       => 'politica-privacidade',
                'title_pt'   => 'Política de Privacidade',
                'title_en'   => 'Privacy Policy',
                'content_pt' => "A Funtastic House valoriza a sua privacidade e o tratamento responsável dos seus dados pessoais.\n\n**1. Dados recolhidos**\nRecolhemos os dados fornecidos no formulário de reserva: nome, email, telefone e datas de estadia.\n\n**2. Finalidade**\nOs seus dados são utilizados exclusivamente para processar o pedido de reserva e estabelecer contacto relacionado com a sua estadia.\n\n**3. Retenção de dados**\nOs seus dados são conservados durante o período necessário à prestação do serviço e ao cumprimento de obrigações legais.\n\n**4. Os seus direitos**\nTem direito de aceder, retificar ou eliminar os seus dados. Para exercer esses direitos, contacte-nos através do email disponível na página de contactos.\n\n**5. Cookies**\nUtilizamos cookies essenciais para o funcionamento do site e cookies de análise para melhorar a experiência de navegação. Pode gerir as suas preferências nas definições do browser.",
                'content_en' => "Funtastic House values your privacy and the responsible handling of your personal data.\n\n**1. Data collected**\nWe collect the data you provide in the booking form: name, email, phone number and stay dates.\n\n**2. Purpose**\nYour data is used exclusively to process your booking request and to communicate with you regarding your stay.\n\n**3. Data retention**\nYour data is kept for as long as necessary to provide the service and comply with legal obligations.\n\n**4. Your rights**\nYou have the right to access, rectify or delete your data. To exercise these rights, contact us via the email on the contacts page.\n\n**5. Cookies**\nWe use essential cookies for site functionality and analytics cookies to improve your browsing experience. You can manage your preferences via your browser settings.",
            ],
            [
                'slug'       => 'termos-condicoes',
                'title_pt'   => 'Termos & Condições',
                'title_en'   => 'Terms & Conditions',
                'content_pt' => "Ao utilizar os serviços da Funtastic House, o cliente aceita os seguintes termos e condições.\n\n**1. Reservas**\nO pedido de reserva não implica confirmação automática. A confirmação é enviada por email após verificação de disponibilidade.\n\n**2. Pagamento**\nO pagamento é efetuado diretamente no check-in ou por acordo prévio com o proprietário. Não são processados pagamentos online.\n\n**3. Check-in e Check-out**\nO check-in é a partir das 15h00 e o check-out até às 11h00. Horários alternativos estão sujeitos a disponibilidade.\n\n**4. Cancelamento**\nEm caso de cancelamento, solicitamos que nos informe com a maior antecedência possível. Cancelamentos tardios podem estar sujeitos a penalização.\n\n**5. Responsabilidade**\nOs hóspedes são responsáveis pelo bom uso das instalações. Danos causados durante a estadia são da responsabilidade do hóspede.",
                'content_en' => "By using the services of Funtastic House, the guest accepts the following terms and conditions.\n\n**1. Reservations**\nA booking request does not imply automatic confirmation. Confirmation is sent by email after availability is verified.\n\n**2. Payment**\nPayment is made directly at check-in or by prior arrangement with the host. No online payments are processed.\n\n**3. Check-in and Check-out**\nCheck-in is from 3:00 PM and check-out is by 11:00 AM. Alternative times are subject to availability.\n\n**4. Cancellation**\nIn case of cancellation, please notify us as early as possible. Late cancellations may be subject to a fee.\n\n**5. Liability**\nGuests are responsible for the proper use of the facilities. Any damage caused during the stay is the guest's responsibility.",
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
