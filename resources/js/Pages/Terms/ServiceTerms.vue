<template>
    <BaseLayout>
        <div class="w-full p-4">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="hidden md:block">
                    <img :src="`/assets/images/terms-conditions.png`" alt="" class="w-full">
                </div>
                <div class="">
                    <h4 class="text-2xl">Введение</h4>
                    <br>
                    <p>1.1. Используя, посещая и/или получая доступ к любой части сайта {{ setting.software_name }} и/или любому поддомену, а также любому сайту или мобильному приложению, принадлежащему нам или управляемому нами (далее – «Сайт»), и/или регистрируя учётную запись на Сайте, вы соглашаетесь соблюдать данные Условия и положения, а также нашу Политику конфиденциальности, Политику в отношении файлов cookie и любые другие правила, применимые к нашим продуктам для ставок или игр, доступным на Сайте (совместно именуемые «Условия»). Считается, что вы приняли и поняли все указанные Условия.</p>
                    <br>
                    <h4 class="text-2xl">2.1. Юридические требования</h4>
                    <br>
                    <p>2.1.1. Под «Учётной записью» понимается учётная запись, зарегистрированная вами на Сайте после принятия и согласия с настоящими Условиями. Регистрируя Учётную запись, вы подтверждаете, что вам исполнилось 18 лет или вы достигли другого минимального совершеннолетия в соответствии с законодательством вашей юрисдикции. Вы самостоятельно несёте ответственность за то, чтобы убедиться в законности использования услуг Сайта в стране вашего проживания. Лица, не достигшие 18 лет, не имеют права использовать Сайт и/или какие-либо доступные на нём сервисы.</p>
                    <br>
                    <h4 class="text-2xl">3. Финансовые операции и вывод средств</h4>
                    <br>
                    <p>3.1. {{ setting.software_name }} оставляет за собой право проверять личность пользователя перед обработкой любых финансовых операций. Это может включать в себя, помимо прочего, запрос документов, удостоверяющих личность, и подтверждение адреса проживания.</p>
                    <p>3.2. {{ setting.software_name }} оставляет за собой право по своему усмотрению устанавливать лимиты на вывод средств. Эти лимиты могут быть изменены в любое время и без предварительного уведомления.</p>
                    <p>3.3. {{ setting.software_name }} не несёт ответственности за любые комиссии или сборы, которые могут взиматься вашим банком или финансовым провайдером.</p>
                    <p>3.4. Финансовые операции должны осуществляться от имени владельца зарегистрированной учётной записи.</p>
                    <br>
                    <h4 class="text-2xl">4. Политика конфиденциальности</h4>
                    <br>
                    <p>4.1. Регистрируя учётную запись на Сайте, вы соглашаетесь с нашей Политикой конфиденциальности. Политика конфиденциальности включена в данные Условия путём ссылки.</p>
                    <br>
                    <h4 class="text-2xl">5. Права интеллектуальной собственности</h4>
                    <br>
                    <p>5.1. Сайт и все материалы, содержащиеся на нем, включая, помимо прочего, логотипы, дизайн, тексты, графику, фотографии, видео, информацию, программное обеспечение, музыку, звук и другие файлы, а также их подбор и расположение (далее – «Контент Сайта»), принадлежат {{ setting.software_name }} или её лицензиарам. Никакие права или интересы в отношении Контента Сайта не передаются вам.</p>
                    <br>
                    <h4 class="text-2xl">6. Расторжение</h4>
                    <br>
                    <p>6.1. {{ setting.software_name }} оставляет за собой право в любой момент по собственному усмотрению прекратить действие вашей учётной записи без предварительного уведомления.</p>
                    <br>
                    <h4 class="text-2xl">7. Изменения в Условиях</h4>
                    <br>
                    <p>7.1. {{ setting.software_name }} оставляет за собой право изменять, дополнять или удалять любые части данных Условий в любое время и по собственному усмотрению. При внесении изменений мы опубликуем пересмотренную версию Условий на Сайте. Дальнейшее использование вами Сайта после размещения таких изменений будет означать ваше согласие с этими изменениями.</p>
                    <br>
                    <h4 class="text-2xl">8. Ограничение ответственности</h4>
                    <br>
                    <p>8.1. {{ setting.software_name }} не несёт ответственности за любые прямые, косвенные, особые, штрафные, побочные, примерные или косвенные убытки либо любые другие убытки любого вида, включая, помимо прочего, утрату возможности использования, упущенную выгоду или потерю данных, независимо от оснований (договор, деликт (включая, но не ограничиваясь небрежностью) или иное), возникающие вследствие или в связи с использованием или невозможностью использования Сайта, услуг или материалов, содержащихся на Сайте или полученных через него.</p>
                    <br>
                    <h4 class="text-2xl">9. Применимое право</h4>
                    <br>
                    <p>9.1. Настоящие Условия регулируются и толкуются в соответствии с законодательством страны, в которой зарегистрирована компания {{ setting.software_name }}. Вы соглашаетесь, что любые судебные или эквивалентные разбирательства, возникающие из настоящих Условий или связанные с ними, будут рассматриваться судами страны, в которой зарегистрирована компания {{ setting.software_name }}, и настоящим вы даёте согласие на исключительную юрисдикцию этих судов.</p>
                    <br>
                    <h4 class="text-2xl">10. Использование хаков или багов</h4>
                    <br>
                    <p>10.1. Строго запрещается использование хаков, багов или любых других способов манипуляции системой для получения несправедливого преимущества. Обнаружение подобной деятельности приведёт к немедленной блокировке учётной записи пользователя и запрету на вывод средств. {{ setting.software_name }} оставляет за собой право предпринять юридические действия в отношении любого пользователя, вовлечённого в такие действия.</p>
                    <br>
                    <h4 class="text-2xl">11. Сервисы третьих сторон</h4>
                    <br>
                    <p>11.1. На Сайте могут быть ссылки на сайты третьих сторон, которые не принадлежат компании {{ setting.software_name }} и не контролируются ею. {{ setting.software_name }} не контролирует и не несёт ответственности за контент, политику конфиденциальности или практики любых сторонних сайтов. Используя Сайт, вы явно освобождаете {{ setting.software_name }} от любой ответственности, возникающей в связи с использованием вами таких сторонних сайтов.</p>
                    <br>
                    <h4 class="text-2xl">12. Возмещение убытков (Индентификация)</h4>
                    <br>
                    <p>12.1. Вы соглашаетесь защищать, возмещать убытки и ограждать {{ setting.software_name }}, её директоров, сотрудников и агентов от любых претензий, исков или требований, включая, помимо прочего, разумные юридические и бухгалтерские расходы, выдвинутые третьими лицами вследствие или в связи с вашим использованием Сайта и услуг или вашим нарушением настоящих Условий.</p>
                    <br>
                    <h4 class="text-2xl">13. Претензии в отношении авторских прав</h4>
                    <br>
                    <p>13.1. Если вы считаете, что ваша работа была скопирована таким образом, что это нарушает авторские права, пожалуйста, предоставьте в своей жалобе следующую информацию: (a) идентификацию защищённой авторским правом работы, которая, по вашему мнению, была нарушена; (b) идентификацию материала на Сайте, который, по вашему мнению, нарушает авторские права; (c) ваш адрес, номер телефона и адрес электронной почты; (d) заявление о том, что вы добросовестно полагаете, что спорное использование не было разрешено владельцем авторских прав, его агентом или законом; (e) заявление, сделанное вами под страхом наказания за дачу ложных показаний, о том, что изложенная в вашей жалобе информация является точной, и что вы являетесь владельцем авторских прав или уполномочены действовать от имени владельца авторских прав.</p>
                    <br>
                    <h4 class="text-2xl">14. Служба поддержки</h4>
                    <br>
                    <p>14.1. Если у вас есть какие-либо вопросы или жалобы относительно Сайта, услуг или настоящих Условий, вы можете связаться со службой поддержки по адресу электронной почты, указанному на Сайте.</p>
                    <br>
                    <h4 class="text-2xl">15. Общие положения</h4>
                    <br>
                    <p>15.1. Если какая-либо часть настоящих Условий будет признана недействительной или неисполнимой в соответствии с применимым законодательством, недействительная или неисполнимая часть будет заменена действующей и подлежащей исполнению нормой, максимально приближённой к намерениям исходной части, а остальные положения Условий сохранят свою силу.</p>
                    <p>15.2. Настоящие Условия, вместе с Политикой конфиденциальности и любыми другими правовыми соглашениями, опубликованными на Сайте, составляют полное соглашение между вами и {{ setting.software_name }} в отношении использования Сайта и услуг.</p>
                    <p>15.3. Любое неосуществление или неприменение компанией {{ setting.software_name }} какого-либо права или положения настоящих Условий не считается отказом от такого права или положения.</p>
                    <br>
                    <h4 class="text-2xl">16. Политика ответственной игры</h4>
                    <br>
                    <p>16.1. {{ setting.software_name }} стремится обеспечить безопасную и ответственную игровую среду. Мы призываем всех наших пользователей играть ответственно и помнить, что игра является формой развлечения, а не способом заработка.</p>
                    <p>16.2. Если вы считаете, что у вас могут быть проблемы с азартными играми, мы рекомендуем обратиться за профессиональной помощью и воспользоваться возможностью самоисключения с нашего сайта.</p>
                    <br>
                    <h4 class="text-2xl">17. Политика самоисключения</h4>
                    <br>
                    <p>17.1. {{ setting.software_name }} предлагает политику самоисключения для тех, кто считает, что у них могут быть проблемы с азартными играми. Если вы решите самоисключиться, ваша учётная запись будет закрыта, и вы не сможете получить доступ к сайту в течение определённого периода времени.</p>
                    <br>
                    <h4 class="text-2xl">18. Политика возврата средств</h4>
                    <br>
                    <p>18.1. {{ setting.software_name }} не осуществляет возврат средств, за исключением исключительных обстоятельств. Любой запрос на возврат будет рассматриваться в индивидуальном порядке.</p>
                    <br>
                    <h4 class="text-2xl">19. Политика учётной записи</h4>
                    <br>
                    <p>19.1. Каждому пользователю строго запрещено регистрировать и поддерживать более одной учётной записи на Сайте. Нарушение данной политики приведёт к немедленному закрытию всех учётных записей, связанных с пользователем, а также к возможным ограничениям на вывод средств.</p>
                    <br>
                    <h4 class="text-2xl">20. Политика проверки возраста</h4>
                    <br>
                    <p>20.1. {{ setting.software_name }} придаёт большое значение проверке возраста. Любой пользователь, в отношении которого имеется подозрение на несовершеннолетие, будет обязан предоставить доказательства возраста. Непредоставление таких доказательств приведёт к закрытию учётной записи пользователя.</p>
                    <br>
                    <h4 class="text-2xl">21. Политика честной игры</h4>
                    <br>
                    <p>21.1. {{ setting.software_name }} стремится обеспечить честную игру для всех своих пользователей. Любой пользователь, уличённый в каком-либо виде манипуляции системой, будет навсегда заблокирован на Сайте.</p>
                </div>
            </div>
        </div>

    </BaseLayout>
</template>


<script>

import BaseLayout from "@/Layouts/BaseLayout.vue";
import {useSettingStore} from "@/Stores/SettingStore.js";

export default {
    props: [],
    components: {BaseLayout},
    data() {
        return {
            isLoading: false,
            setting: null,
        }
    },
    setup(props) {
        return {};
    },
    computed: {

    },
    mounted() {

    },
    methods: {
        getSetting: function() {
            const _this = this;
            const settingStore = useSettingStore();
            const settingData = settingStore.setting;

            if(settingData) {
                _this.setting = settingData;
            }
        },
    },
    created() {
        this.getSetting();
    },
    watch: {

    },
};
</script>

<style scoped>

</style>
