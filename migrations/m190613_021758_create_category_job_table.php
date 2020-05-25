<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_job}}`.
 */
class m190613_021758_create_category_job_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_job}}', [
            'id' => $this->primaryKey(),
            'status' => $this->integer()->defaultValue(10),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'background_color' => $this->string(),
            'image_path' => $this->string(),
            'tree' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->string(),
            'show_on_main_page' => $this->integer()->defaultValue(20)->notNull(),
            'main_page_order' => $this->integer()->defaultValue(1000)->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->addCommentOnColumn('category_job', 'status', '10 - default , 20 - hidden');

        // insert standart categry list
        $this->insert('category_job', [
            "id" =>  "1",
            "status" =>  "10",
            "name" =>  "root",
            "lft" =>  "1",
            "rgt" =>  "882",
            "depth" =>  "0",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "2",
            "status" =>  "10",
            "name" =>  "Неквалифицированная работа",
            "lft" =>  "2",
            "rgt" =>  "23",
            "depth" =>  "1",
            "meta_keywords" =>  "",
            "meta_description" =>  "",
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "3",
            "status" =>  "10",
            "name" =>  "Перевозки",
            "lft" =>  "24",
            "rgt" =>  "81",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "4",
            "status" =>  "10",
            "name" =>  "Производство",
            "lft" =>  "82",
            "rgt" =>  "139",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "5",
            "status" =>  "10",
            "name" =>  "Строительство",
            "lft" =>  "140",
            "rgt" =>  "257",
            "depth" =>  "1",
            "meta_keywords" =>  "",
            "meta_description" =>  "",
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "6",
            "status" =>  "10",
            "name" =>  "Сфера обслуживания, спорт, красота",
            "lft" =>  "258",
            "rgt" =>  "337",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "7",
            "status" =>  "10",
            "name" =>  "Отельно-ресторанная сфера",
            "lft" =>  "338",
            "rgt" =>  "385",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "8",
            "status" =>  "10",
            "name" =>  "IT",
            "lft" =>  "386",
            "rgt" =>  "419",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "9",
            "status" =>  "10",
            "name" =>  "Инженерная сфера",
            "lft" =>  "420",
            "rgt" =>  "455",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "10",
            "status" =>  "10",
            "name" =>  "Легкая промышленность",
            "lft" =>  "456",
            "rgt" =>  "485",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "11",
            "status" =>  "10",
            "name" =>  "Лесничество",
            "lft" =>  "486",
            "rgt" =>  "495",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "12",
            "status" =>  "10",
            "name" =>  "Медицина",
            "lft" =>  "496",
            "rgt" =>  "551",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "13",
            "status" =>  "10",
            "name" =>  "Мясная промышленность",
            "lft" =>  "552",
            "rgt" =>  "561",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "14",
            "status" =>  "10",
            "name" =>  "Образование",
            "lft" =>  "562",
            "rgt" =>  "587",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "15",
            "status" =>  "10",
            "name" =>  "Полиграфия",
            "lft" =>  "588",
            "rgt" =>  "601",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "16",
            "status" =>  "10",
            "name" =>  "Работа в офисе",
            "lft" =>  "602",
            "rgt" =>  "677",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
            "show_on_main_page" =>  '10',
        ]);
        $this->insert('category_job', [
            "id" =>  "17",
            "status" =>  "10",
            "name" =>  "Розничная торговля",
            "lft" =>  "678",
            "rgt" =>  "703",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "18",
            "status" =>  "10",
            "name" =>  "Сельское хозяйство и садовничество",
            "lft" =>  "704",
            "rgt" =>  "733",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "19",
            "status" =>  "10",
            "name" =>  "Складская сфера",
            "lft" =>  "734",
            "rgt" =>  "745",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "20",
            "status" =>  "10",
            "name" =>  "Сфера развлечений и искусство",
            "lft" =>  "746",
            "rgt" =>  "803",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "21",
            "status" =>  "10",
            "name" =>  "Тяжелая промышленность",
            "lft" =>  "804",
            "rgt" =>  "835",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "22",
            "status" =>  "10",
            "name" =>  "Финансы, бухгалтерия, право",
            "lft" =>  "836",
            "rgt" =>  "855",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "23",
            "status" =>  "10",
            "name" =>  "Электроэнергетика",
            "lft" =>  "856",
            "rgt" =>  "875",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "24",
            "status" =>  "10",
            "name" =>  "Другое",
            "lft" =>  "876",
            "rgt" =>  "881",
            "depth" =>  "1",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "25",
            "status" =>  "10",
            "name" =>  "Буфетчица",
            "lft" =>  "3",
            "rgt" =>  "4",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "26",
            "status" =>  "10",
            "name" =>  "Помощник на кухне",
            "lft" =>  "5",
            "rgt" =>  "6",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "27",
            "status" =>  "10",
            "name" =>  "Помощник пекаря",
            "lft" =>  "7",
            "rgt" =>  "8",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "28",
            "status" =>  "10",
            "name" =>  "Работник на производство",
            "lft" =>  "9",
            "rgt" =>  "10",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "29",
            "status" =>  "10",
            "name" =>  "Работник склада",
            "lft" =>  "11",
            "rgt" =>  "12",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "30",
            "status" =>  "10",
            "name" =>  "Разнорабочий на стройку",
            "lft" =>  "13",
            "rgt" =>  "14",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "31",
            "status" =>  "10",
            "name" =>  "Сборщик на производство",
            "lft" =>  "15",
            "rgt" =>  "16",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "32",
            "status" =>  "10",
            "name" =>  "Сборщик фруктов\/овощей",
            "lft" =>  "17",
            "rgt" =>  "18",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "33",
            "status" =>  "10",
            "name" =>  "Сельхозрабочий",
            "lft" =>  "19",
            "rgt" =>  "20",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "34",
            "status" =>  "10",
            "name" =>  "Фасовщик",
            "lft" =>  "21",
            "rgt" =>  "22",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "35",
            "status" =>  "10",
            "name" =>  "Боцман",
            "lft" =>  "25",
            "rgt" =>  "26",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "36",
            "status" =>  "10",
            "name" =>  "Водитель",
            "lft" =>  "27",
            "rgt" =>  "28",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "37",
            "status" =>  "10",
            "name" =>  "Водитель автобуса",
            "lft" =>  "29",
            "rgt" =>  "30",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "38",
            "status" =>  "10",
            "name" =>  "Водитель автовоза",
            "lft" =>  "31",
            "rgt" =>  "32",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "39",
            "status" =>  "10",
            "name" =>  "Водитель автокрана",
            "lft" =>  "33",
            "rgt" =>  "34",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "40",
            "status" =>  "10",
            "name" =>  "Водитель бульдозера",
            "lft" =>  "35",
            "rgt" =>  "36",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "41",
            "status" =>  "10",
            "name" =>  "Водитель грузового транспорта",
            "lft" =>  "37",
            "rgt" =>  "38",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "42",
            "status" =>  "10",
            "name" =>  "Водитель комбайна",
            "lft" =>  "39",
            "rgt" =>  "40",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "43",
            "status" =>  "10",
            "name" =>  "Водитель лесовоза с манипулятором",
            "lft" =>  "41",
            "rgt" =>  "42",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "44",
            "status" =>  "10",
            "name" =>  "Водитель самосвала",
            "lft" =>  "43",
            "rgt" =>  "44",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "45",
            "status" =>  "10",
            "name" =>  "Водитель экспедитор",
            "lft" =>  "45",
            "rgt" =>  "46",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "46",
            "status" =>  "10",
            "name" =>  "Дальнобойщик",
            "lft" =>  "47",
            "rgt" =>  "48",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "47",
            "status" =>  "10",
            "name" =>  "Диспетчер",
            "lft" =>  "49",
            "rgt" =>  "50",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "48",
            "status" =>  "10",
            "name" =>  "Капитан судна",
            "lft" =>  "51",
            "rgt" =>  "52",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "49",
            "status" =>  "10",
            "name" =>  "Контроллер",
            "lft" =>  "53",
            "rgt" =>  "54",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "50",
            "status" =>  "10",
            "name" =>  "Курьер",
            "lft" =>  "55",
            "rgt" =>  "56",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "51",
            "status" =>  "10",
            "name" =>  "Летчик - инструктор",
            "lft" =>  "57",
            "rgt" =>  "58",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "52",
            "status" =>  "10",
            "name" =>  "Логист",
            "lft" =>  "59",
            "rgt" =>  "60",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "53",
            "status" =>  "10",
            "name" =>  "Машинист асфальтоукладчика",
            "lft" =>  "61",
            "rgt" =>  "62",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "54",
            "status" =>  "10",
            "name" =>  "Машинист электровоза",
            "lft" =>  "63",
            "rgt" =>  "64",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "55",
            "status" =>  "10",
            "name" =>  "Монтер путей",
            "lft" =>  "65",
            "rgt" =>  "66",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "56",
            "status" =>  "10",
            "name" =>  "Моторист",
            "lft" =>  "67",
            "rgt" =>  "68",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "57",
            "status" =>  "10",
            "name" =>  "Оператор машин",
            "lft" =>  "69",
            "rgt" =>  "70",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "58",
            "status" =>  "10",
            "name" =>  "Оператор телескопического погрузчика",
            "lft" =>  "71",
            "rgt" =>  "72",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "59",
            "status" =>  "10",
            "name" =>  "Регулировщик скорости движения вагонов",
            "lft" =>  "73",
            "rgt" =>  "74",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "60",
            "status" =>  "10",
            "name" =>  "Таксист",
            "lft" =>  "75",
            "rgt" =>  "76",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "61",
            "status" =>  "10",
            "name" =>  "Таможенный брокер",
            "lft" =>  "77",
            "rgt" =>  "78",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "62",
            "status" =>  "10",
            "name" =>  "Тракторист",
            "lft" =>  "79",
            "rgt" =>  "80",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "63",
            "status" =>  "10",
            "name" =>  "Жестянщик",
            "lft" =>  "83",
            "rgt" =>  "84",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "64",
            "status" =>  "10",
            "name" =>  "Закройщик обуви",
            "lft" =>  "85",
            "rgt" =>  "86",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "65",
            "status" =>  "10",
            "name" =>  "Закройщик одежды",
            "lft" =>  "87",
            "rgt" =>  "88",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "66",
            "status" =>  "10",
            "name" =>  "Изготовитель палет",
            "lft" =>  "89",
            "rgt" =>  "90",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "67",
            "status" =>  "10",
            "name" =>  "Инженер-технолог",
            "lft" =>  "91",
            "rgt" =>  "92",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "68",
            "status" =>  "10",
            "name" =>  "Клейщик этикеток",
            "lft" =>  "93",
            "rgt" =>  "94",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "69",
            "status" =>  "10",
            "name" =>  "Кузнец",
            "lft" =>  "95",
            "rgt" =>  "96",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "70",
            "status" =>  "10",
            "name" =>  "Мебельный лакер",
            "lft" =>  "97",
            "rgt" =>  "98",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "71",
            "status" =>  "10",
            "name" =>  "Обойщик",
            "lft" =>  "99",
            "rgt" =>  "100",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "72",
            "status" =>  "10",
            "name" =>  "Оператор линии",
            "lft" =>  "101",
            "rgt" =>  "102",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "73",
            "status" =>  "10",
            "name" =>  "Оператор машин",
            "lft" =>  "103",
            "rgt" =>  "104",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "74",
            "status" =>  "10",
            "name" =>  "Оператор производства",
            "lft" =>  "105",
            "rgt" =>  "106",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "75",
            "status" =>  "10",
            "name" =>  "Оператор телескопического погрузчика",
            "lft" =>  "107",
            "rgt" =>  "108",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "76",
            "status" =>  "10",
            "name" =>  "Оператор ЧПУ",
            "lft" =>  "109",
            "rgt" =>  "110",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "77",
            "status" =>  "10",
            "name" =>  "Печатник",
            "lft" =>  "111",
            "rgt" =>  "112",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "78",
            "status" =>  "10",
            "name" =>  "Работник на производство",
            "lft" =>  "113",
            "rgt" =>  "114",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "79",
            "status" =>  "10",
            "name" =>  "Работник склада",
            "lft" =>  "115",
            "rgt" =>  "116",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "80",
            "status" =>  "10",
            "name" =>  "Работник типографии",
            "lft" =>  "117",
            "rgt" =>  "118",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "81",
            "status" =>  "10",
            "name" =>  "Сборщик мебели",
            "lft" =>  "119",
            "rgt" =>  "120",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "82",
            "status" =>  "10",
            "name" =>  "Сборщик на производство",
            "lft" =>  "121",
            "rgt" =>  "122",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "83",
            "status" =>  "10",
            "name" =>  "Сортировщик",
            "lft" =>  "123",
            "rgt" =>  "124",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "84",
            "status" =>  "10",
            "name" =>  "Технолог мельничного комплекса",
            "lft" =>  "125",
            "rgt" =>  "126",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "85",
            "status" =>  "10",
            "name" =>  "Токарь",
            "lft" =>  "127",
            "rgt" =>  "128",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "86",
            "status" =>  "10",
            "name" =>  "Упаковщик",
            "lft" =>  "129",
            "rgt" =>  "130",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "87",
            "status" =>  "10",
            "name" =>  "Фасовщик",
            "lft" =>  "131",
            "rgt" =>  "132",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "88",
            "status" =>  "10",
            "name" =>  "Швея",
            "lft" =>  "133",
            "rgt" =>  "134",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "89",
            "status" =>  "10",
            "name" =>  "Электросварщик",
            "lft" =>  "135",
            "rgt" =>  "136",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "90",
            "status" =>  "10",
            "name" =>  "Ювелир",
            "lft" =>  "137",
            "rgt" =>  "138",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "91",
            "status" =>  "10",
            "name" =>  "Арматурщик",
            "lft" =>  "141",
            "rgt" =>  "142",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "92",
            "status" =>  "10",
            "name" =>  "Бетонщик",
            "lft" =>  "143",
            "rgt" =>  "144",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "93",
            "status" =>  "10",
            "name" =>  "Бригадир",
            "lft" =>  "145",
            "rgt" =>  "146",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "94",
            "status" =>  "10",
            "name" =>  "Бурильщик",
            "lft" =>  "147",
            "rgt" =>  "148",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "95",
            "status" =>  "10",
            "name" =>  "Газорезчик",
            "lft" =>  "149",
            "rgt" =>  "150",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "96",
            "status" =>  "10",
            "name" =>  "Геодезист",
            "lft" =>  "151",
            "rgt" =>  "152",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "97",
            "status" =>  "10",
            "name" =>  "Гидравлик-инсталлятор",
            "lft" =>  "153",
            "rgt" =>  "154",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "98",
            "status" =>  "10",
            "name" =>  "Гипсокартонщик",
            "lft" =>  "155",
            "rgt" =>  "156",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "99",
            "status" =>  "10",
            "name" =>  "Дорожный строитель",
            "lft" =>  "157",
            "rgt" =>  "158",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "100",
            "status" =>  "10",
            "name" =>  "Каменщик",
            "lft" =>  "159",
            "rgt" =>  "160",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "101",
            "status" =>  "10",
            "name" =>  "Крановщик",
            "lft" =>  "161",
            "rgt" =>  "162",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "102",
            "status" =>  "10",
            "name" =>  "Кровельщик",
            "lft" =>  "163",
            "rgt" =>  "164",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "103",
            "status" =>  "10",
            "name" =>  "Маляр",
            "lft" =>  "165",
            "rgt" =>  "166",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "104",
            "status" =>  "10",
            "name" =>  "Маляр-Штукатур",
            "lft" =>  "167",
            "rgt" =>  "168",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "105",
            "status" =>  "10",
            "name" =>  "Маляр по дереву",
            "lft" =>  "169",
            "rgt" =>  "170",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "106",
            "status" =>  "10",
            "name" =>  "Машинист автокрана",
            "lft" =>  "171",
            "rgt" =>  "172",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "107",
            "status" =>  "10",
            "name" =>  "Машинист асфальтоукладчика",
            "lft" =>  "173",
            "rgt" =>  "174",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "108",
            "status" =>  "10",
            "name" =>  "Машинист мостового крана",
            "lft" =>  "175",
            "rgt" =>  "176",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "109",
            "status" =>  "10",
            "name" =>  "Машинист фронтального автопогрузчика",
            "lft" =>  "177",
            "rgt" =>  "178",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "110",
            "status" =>  "10",
            "name" =>  "Машинист экскаватора",
            "lft" =>  "179",
            "rgt" =>  "180",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "111",
            "status" =>  "10",
            "name" =>  "Монтажник",
            "lft" =>  "181",
            "rgt" =>  "182",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "112",
            "status" =>  "10",
            "name" =>  "Монтажник вентиляционных инсталляций",
            "lft" =>  "183",
            "rgt" =>  "184",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "113",
            "status" =>  "10",
            "name" =>  "Монтажник водосточных систем",
            "lft" =>  "185",
            "rgt" =>  "186",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "114",
            "status" =>  "10",
            "name" =>  "Монтажник  климатических инсталляций",
            "lft" =>  "187",
            "rgt" =>  "188",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "115",
            "status" =>  "10",
            "name" =>  "Монтажник металоконструкций",
            "lft" =>  "189",
            "rgt" =>  "190",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "116",
            "status" =>  "10",
            "name" =>  "Монтажник окон",
            "lft" =>  "191",
            "rgt" =>  "192",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "117",
            "status" =>  "10",
            "name" =>  "Монтажник систем кондиционирования",
            "lft" =>  "193",
            "rgt" =>  "194",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "118",
            "status" =>  "10",
            "name" =>  "Монтажник систем отопления",
            "lft" =>  "195",
            "rgt" =>  "196",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "119",
            "status" =>  "10",
            "name" =>  "Монтер лесов",
            "lft" =>  "197",
            "rgt" =>  "198",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "120",
            "status" =>  "10",
            "name" =>  "Монтировщик",
            "lft" =>  "199",
            "rgt" =>  "200",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "121",
            "status" =>  "10",
            "name" =>  "Мостильщик",
            "lft" =>  "201",
            "rgt" =>  "202",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "122",
            "status" =>  "10",
            "name" =>  "Оператор буровых машин",
            "lft" =>  "203",
            "rgt" =>  "204",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "123",
            "status" =>  "10",
            "name" =>  "Оператор гусеничного экскаватора",
            "lft" =>  "205",
            "rgt" =>  "206",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "124",
            "status" =>  "10",
            "name" =>  "Оператор машин",
            "lft" =>  "207",
            "rgt" =>  "208",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "125",
            "status" =>  "10",
            "name" =>  "Отделочник",
            "lft" =>  "209",
            "rgt" =>  "210",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "126",
            "status" =>  "10",
            "name" =>  "Пескоструйщик",
            "lft" =>  "211",
            "rgt" =>  "212",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "127",
            "status" =>  "10",
            "name" =>  "Плиточник",
            "lft" =>  "213",
            "rgt" =>  "214",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "128",
            "status" =>  "10",
            "name" =>  "Плотник",
            "lft" =>  "215",
            "rgt" =>  "216",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "129",
            "status" =>  "10",
            "name" =>  "Помощник сварщика",
            "lft" =>  "217",
            "rgt" =>  "218",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "130",
            "status" =>  "10",
            "name" =>  "Промышленный альпинист",
            "lft" =>  "219",
            "rgt" =>  "220",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "131",
            "status" =>  "10",
            "name" =>  "Прораб",
            "lft" =>  "221",
            "rgt" =>  "222",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "132",
            "status" =>  "10",
            "name" =>  "Разнорабочий на стройку",
            "lft" =>  "223",
            "rgt" =>  "224",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "133",
            "status" =>  "10",
            "name" =>  "Реставратор",
            "lft" =>  "225",
            "rgt" =>  "226",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "134",
            "status" =>  "10",
            "name" =>  "Руководитель стройки",
            "lft" =>  "227",
            "rgt" =>  "228",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "135",
            "status" =>  "10",
            "name" =>  "Сварщик",
            "lft" =>  "229",
            "rgt" =>  "230",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "136",
            "status" =>  "10",
            "name" =>  "Слесарь",
            "lft" =>  "231",
            "rgt" =>  "232",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "137",
            "status" =>  "10",
            "name" =>  "Столяр",
            "lft" =>  "233",
            "rgt" =>  "234",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "138",
            "status" =>  "10",
            "name" =>  "Строитель",
            "lft" =>  "235",
            "rgt" =>  "236",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "139",
            "status" =>  "10",
            "name" =>  "Строительный архитектор",
            "lft" =>  "237",
            "rgt" =>  "238",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "140",
            "status" =>  "10",
            "name" =>  "Строительный инженер",
            "lft" =>  "239",
            "rgt" =>  "240",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "141",
            "status" =>  "10",
            "name" =>  "Токарь",
            "lft" =>  "241",
            "rgt" =>  "242",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "142",
            "status" =>  "10",
            "name" =>  "Фасадчик",
            "lft" =>  "243",
            "rgt" =>  "244",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "143",
            "status" =>  "10",
            "name" =>  "Фрезеровщик",
            "lft" =>  "245",
            "rgt" =>  "246",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "144",
            "status" =>  "10",
            "name" =>  "Шлифовщик",
            "lft" =>  "247",
            "rgt" =>  "248",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "145",
            "status" =>  "10",
            "name" =>  "Шпаклевщик",
            "lft" =>  "249",
            "rgt" =>  "250",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "146",
            "status" =>  "10",
            "name" =>  "Штукатур",
            "lft" =>  "251",
            "rgt" =>  "252",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "147",
            "status" =>  "10",
            "name" =>  "Экскаваторщик",
            "lft" =>  "253",
            "rgt" =>  "254",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "148",
            "status" =>  "10",
            "name" =>  "Электросварщик",
            "lft" =>  "255",
            "rgt" =>  "256",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "149",
            "status" =>  "10",
            "name" =>  "Администратор",
            "lft" =>  "259",
            "rgt" =>  "260",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "150",
            "status" =>  "10",
            "name" =>  "Бортпроводник",
            "lft" =>  "261",
            "rgt" =>  "262",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "151",
            "status" =>  "10",
            "name" =>  "Буфетчица",
            "lft" =>  "263",
            "rgt" =>  "264",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "152",
            "status" =>  "10",
            "name" =>  "Ветеринар",
            "lft" =>  "265",
            "rgt" =>  "266",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "153",
            "status" =>  "10",
            "name" =>  "Визажист",
            "lft" =>  "267",
            "rgt" =>  "268",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "154",
            "status" =>  "10",
            "name" =>  "Воспитатель",
            "lft" =>  "269",
            "rgt" =>  "270",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "155",
            "status" =>  "10",
            "name" =>  "Горничная",
            "lft" =>  "271",
            "rgt" =>  "272",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "156",
            "status" =>  "10",
            "name" =>  "Гувернантка",
            "lft" =>  "273",
            "rgt" =>  "274",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "157",
            "status" =>  "10",
            "name" =>  "Дворник",
            "lft" =>  "275",
            "rgt" =>  "276",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "158",
            "status" =>  "10",
            "name" =>  "Диетолог",
            "lft" =>  "277",
            "rgt" =>  "278",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "159",
            "status" =>  "10",
            "name" =>  "Домработница",
            "lft" =>  "279",
            "rgt" =>  "280",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "160",
            "status" =>  "10",
            "name" =>  "Контроллер",
            "lft" =>  "281",
            "rgt" =>  "282",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "161",
            "status" =>  "10",
            "name" =>  "Косметолог",
            "lft" =>  "283",
            "rgt" =>  "284",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "162",
            "status" =>  "10",
            "name" =>  "Массажист",
            "lft" =>  "285",
            "rgt" =>  "286",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "163",
            "status" =>  "10",
            "name" =>  "Мастер депиляции",
            "lft" =>  "287",
            "rgt" =>  "288",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "164",
            "status" =>  "10",
            "name" =>  "Мастер маникюра\/педикюра",
            "lft" =>  "289",
            "rgt" =>  "290",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "165",
            "status" =>  "10",
            "name" =>  "Мастер наращивания ресниц",
            "lft" =>  "291",
            "rgt" =>  "292",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "166",
            "status" =>  "10",
            "name" =>  "Мастер тату",
            "lft" =>  "293",
            "rgt" =>  "294",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "167",
            "status" =>  "10",
            "name" =>  "Мастер татуажа",
            "lft" =>  "295",
            "rgt" =>  "296",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "168",
            "status" =>  "10",
            "name" =>  "Механик",
            "lft" =>  "297",
            "rgt" =>  "298",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "169",
            "status" =>  "10",
            "name" =>  "Няня",
            "lft" =>  "299",
            "rgt" =>  "300",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "170",
            "status" =>  "10",
            "name" =>  "Опекун",
            "lft" =>  "301",
            "rgt" =>  "302",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "171",
            "status" =>  "10",
            "name" =>  "Охранник",
            "lft" =>  "303",
            "rgt" =>  "304",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "172",
            "status" =>  "10",
            "name" =>  "Парикмахер",
            "lft" =>  "305",
            "rgt" =>  "306",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "173",
            "status" =>  "10",
            "name" =>  "Персональный тренер",
            "lft" =>  "307",
            "rgt" =>  "308",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "174",
            "status" =>  "10",
            "name" =>  "Полицейский",
            "lft" =>  "309",
            "rgt" =>  "310",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "175",
            "status" =>  "10",
            "name" =>  "Психолог",
            "lft" =>  "311",
            "rgt" =>  "312",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "176",
            "status" =>  "10",
            "name" =>  "Работник типографии",
            "lft" =>  "313",
            "rgt" =>  "314",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "177",
            "status" =>  "10",
            "name" =>  "Реставратор",
            "lft" =>  "315",
            "rgt" =>  "316",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "178",
            "status" =>  "10",
            "name" =>  "Сантехник",
            "lft" =>  "317",
            "rgt" =>  "318",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "179",
            "status" =>  "10",
            "name" =>  "Сиделка",
            "lft" =>  "319",
            "rgt" =>  "320",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "180",
            "status" =>  "10",
            "name" =>  "Спасатель",
            "lft" =>  "321",
            "rgt" =>  "322",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "181",
            "status" =>  "10",
            "name" =>  "Стилист",
            "lft" =>  "323",
            "rgt" =>  "324",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "182",
            "status" =>  "10",
            "name" =>  "Стюардесса",
            "lft" =>  "325",
            "rgt" =>  "326",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "183",
            "status" =>  "10",
            "name" =>  "Телохранитель",
            "lft" =>  "327",
            "rgt" =>  "328",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "184",
            "status" =>  "10",
            "name" =>  "Уборщица",
            "lft" =>  "329",
            "rgt" =>  "330",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "185",
            "status" =>  "10",
            "name" =>  "Фитнес-тренер",
            "lft" =>  "331",
            "rgt" =>  "332",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "186",
            "status" =>  "10",
            "name" =>  "Футболист",
            "lft" =>  "333",
            "rgt" =>  "334",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "187",
            "status" =>  "10",
            "name" =>  "Художник",
            "lft" =>  "335",
            "rgt" =>  "336",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "188",
            "status" =>  "10",
            "name" =>  "Администратор",
            "lft" =>  "339",
            "rgt" =>  "340",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "189",
            "status" =>  "10",
            "name" =>  "Бариста",
            "lft" =>  "341",
            "rgt" =>  "342",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "190",
            "status" =>  "10",
            "name" =>  "Бармен",
            "lft" =>  "343",
            "rgt" =>  "344",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "191",
            "status" =>  "10",
            "name" =>  "Винодел",
            "lft" =>  "345",
            "rgt" =>  "346",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "192",
            "status" =>  "10",
            "name" =>  "Дегустатор",
            "lft" =>  "347",
            "rgt" =>  "348",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "193",
            "status" =>  "10",
            "name" =>  "Кондитер",
            "lft" =>  "349",
            "rgt" =>  "350",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "194",
            "status" =>  "10",
            "name" =>  "Мангальщик",
            "lft" =>  "351",
            "rgt" =>  "352",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "195",
            "status" =>  "10",
            "name" =>  "Официант",
            "lft" =>  "353",
            "rgt" =>  "354",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "196",
            "status" =>  "10",
            "name" =>  "Пекарь",
            "lft" =>  "355",
            "rgt" =>  "356",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "197",
            "status" =>  "10",
            "name" =>  "Пивовар",
            "lft" =>  "357",
            "rgt" =>  "358",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "198",
            "status" =>  "10",
            "name" =>  "Пиццайоло",
            "lft" =>  "359",
            "rgt" =>  "360",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "199",
            "status" =>  "10",
            "name" =>  "Повар",
            "lft" =>  "361",
            "rgt" =>  "362",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "200",
            "status" =>  "10",
            "name" =>  "Помощник на кухне",
            "lft" =>  "363",
            "rgt" =>  "364",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "201",
            "status" =>  "10",
            "name" =>  "Помощник пекаря",
            "lft" =>  "365",
            "rgt" =>  "366",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "202",
            "status" =>  "10",
            "name" =>  "Посудомойщица",
            "lft" =>  "367",
            "rgt" =>  "368",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "203",
            "status" =>  "10",
            "name" =>  "Работник в гостинице",
            "lft" =>  "369",
            "rgt" =>  "370",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "204",
            "status" =>  "10",
            "name" =>  "Работник на кухне",
            "lft" =>  "371",
            "rgt" =>  "372",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "205",
            "status" =>  "10",
            "name" =>  "Суши-шеф",
            "lft" =>  "373",
            "rgt" =>  "374",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "206",
            "status" =>  "10",
            "name" =>  "Сушист",
            "lft" =>  "375",
            "rgt" =>  "376",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "207",
            "status" =>  "10",
            "name" =>  "Технолог общественного питания",
            "lft" =>  "377",
            "rgt" =>  "378",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "208",
            "status" =>  "10",
            "name" =>  "Уборщица",
            "lft" =>  "379",
            "rgt" =>  "380",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "209",
            "status" =>  "10",
            "name" =>  "Хостесс",
            "lft" =>  "381",
            "rgt" =>  "382",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "210",
            "status" =>  "10",
            "name" =>  "Шеф-повар",
            "lft" =>  "383",
            "rgt" =>  "384",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "211",
            "status" =>  "10",
            "name" =>  "IT Helpdesk",
            "lft" =>  "387",
            "rgt" =>  "388",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "212",
            "status" =>  "10",
            "name" =>  "Контент-менеджер",
            "lft" =>  "389",
            "rgt" =>  "390",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "213",
            "status" =>  "10",
            "name" =>  "Программист",
            "lft" =>  "391",
            "rgt" =>  "392",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "214",
            "status" =>  "10",
            "name" =>  "Системный администратор",
            "lft" =>  "393",
            "rgt" =>  "394",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "215",
            "status" =>  "10",
            "name" =>  "Автомобильная сфера",
            "lft" =>  "395",
            "rgt" =>  "396",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "216",
            "status" =>  "10",
            "name" =>  "Автоелектрик",
            "lft" =>  "397",
            "rgt" =>  "398",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "217",
            "status" =>  "10",
            "name" =>  "Автомаляр",
            "lft" =>  "399",
            "rgt" =>  "400",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "218",
            "status" =>  "10",
            "name" =>  "Автомеханик",
            "lft" =>  "401",
            "rgt" =>  "402",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "219",
            "status" =>  "10",
            "name" =>  "Автомойщик",
            "lft" =>  "403",
            "rgt" =>  "404",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "220",
            "status" =>  "10",
            "name" =>  "Автослесарь",
            "lft" =>  "405",
            "rgt" =>  "406",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "221",
            "status" =>  "10",
            "name" =>  "Автослесарь арматурщик",
            "lft" =>  "407",
            "rgt" =>  "408",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "222",
            "status" =>  "10",
            "name" =>  "Мойщик автомобилей",
            "lft" =>  "409",
            "rgt" =>  "410",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "223",
            "status" =>  "10",
            "name" =>  "Помощник автомаляра",
            "lft" =>  "411",
            "rgt" =>  "412",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "224",
            "status" =>  "10",
            "name" =>  "Продавец автомобилей",
            "lft" =>  "413",
            "rgt" =>  "414",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "225",
            "status" =>  "10",
            "name" =>  "Работник СТО",
            "lft" =>  "415",
            "rgt" =>  "416",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "226",
            "status" =>  "10",
            "name" =>  "Шиномонтажник",
            "lft" =>  "417",
            "rgt" =>  "418",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "227",
            "status" =>  "10",
            "name" =>  "Manual Test Engineer",
            "lft" =>  "421",
            "rgt" =>  "422",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "228",
            "status" =>  "10",
            "name" =>  "Инженер",
            "lft" =>  "423",
            "rgt" =>  "424",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "229",
            "status" =>  "10",
            "name" =>  "Инженер-гидротехник",
            "lft" =>  "425",
            "rgt" =>  "426",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "230",
            "status" =>  "10",
            "name" =>  "Инженер-конструктор",
            "lft" =>  "427",
            "rgt" =>  "428",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "231",
            "status" =>  "10",
            "name" =>  "Инженер-металлург",
            "lft" =>  "429",
            "rgt" =>  "430",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "232",
            "status" =>  "10",
            "name" =>  "Инженер-метролог",
            "lft" =>  "431",
            "rgt" =>  "432",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "233",
            "status" =>  "10",
            "name" =>  "Инженер-механик",
            "lft" =>  "433",
            "rgt" =>  "434",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "234",
            "status" =>  "10",
            "name" =>  "Инженер-проектировщик",
            "lft" =>  "435",
            "rgt" =>  "436",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "235",
            "status" =>  "10",
            "name" =>  "Инженер-химик",
            "lft" =>  "437",
            "rgt" =>  "438",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "236",
            "status" =>  "10",
            "name" =>  "Инженер-электрик",
            "lft" =>  "439",
            "rgt" =>  "440",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "237",
            "status" =>  "10",
            "name" =>  "Инженер-электронщик",
            "lft" =>  "441",
            "rgt" =>  "442",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "238",
            "status" =>  "10",
            "name" =>  "Инженер-энергетик",
            "lft" =>  "443",
            "rgt" =>  "444",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "239",
            "status" =>  "10",
            "name" =>  "Инженер гидросооружений",
            "lft" =>  "445",
            "rgt" =>  "446",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "240",
            "status" =>  "10",
            "name" =>  "Инженер Ж\/Д транспорта",
            "lft" =>  "447",
            "rgt" =>  "448",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "241",
            "status" =>  "10",
            "name" =>  "Инженер связи",
            "lft" =>  "449",
            "rgt" =>  "450",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "242",
            "status" =>  "10",
            "name" =>  "Радиотехник",
            "lft" =>  "451",
            "rgt" =>  "452",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "243",
            "status" =>  "10",
            "name" =>  "Электромеханик",
            "lft" =>  "453",
            "rgt" =>  "454",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "244",
            "status" =>  "10",
            "name" =>  "Вязальщик",
            "lft" =>  "457",
            "rgt" =>  "458",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "245",
            "status" =>  "10",
            "name" =>  "Директор",
            "lft" =>  "459",
            "rgt" =>  "460",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "246",
            "status" =>  "10",
            "name" =>  "Закройщик обуви",
            "lft" =>  "461",
            "rgt" =>  "462",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "247",
            "status" =>  "10",
            "name" =>  "Мездрильщик",
            "lft" =>  "463",
            "rgt" =>  "464",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "248",
            "status" =>  "10",
            "name" =>  "Меховщик",
            "lft" =>  "465",
            "rgt" =>  "466",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "249",
            "status" =>  "10",
            "name" =>  "Модельер",
            "lft" =>  "467",
            "rgt" =>  "468",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "250",
            "status" =>  "10",
            "name" =>  "Оператор Call-центра",
            "lft" =>  "469",
            "rgt" =>  "470",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "251",
            "status" =>  "10",
            "name" =>  "Офис менеджер",
            "lft" =>  "471",
            "rgt" =>  "472",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "252",
            "status" =>  "10",
            "name" =>  "Портной",
            "lft" =>  "473",
            "rgt" =>  "474",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "253",
            "status" =>  "10",
            "name" =>  "Секретарь",
            "lft" =>  "475",
            "rgt" =>  "476",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "254",
            "status" =>  "10",
            "name" =>  "Страховой агент",
            "lft" =>  "477",
            "rgt" =>  "478",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "255",
            "status" =>  "10",
            "name" =>  "Технолог мельничного комплекса",
            "lft" =>  "479",
            "rgt" =>  "480",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "256",
            "status" =>  "10",
            "name" =>  "Ткач",
            "lft" =>  "481",
            "rgt" =>  "482",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "257",
            "status" =>  "10",
            "name" =>  "Часовой мастер",
            "lft" =>  "483",
            "rgt" =>  "484",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "258",
            "status" =>  "10",
            "name" =>  "Лесник",
            "lft" =>  "487",
            "rgt" =>  "488",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "259",
            "status" =>  "10",
            "name" =>  "Лесоруб",
            "lft" =>  "489",
            "rgt" =>  "490",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "260",
            "status" =>  "10",
            "name" =>  "Охранник леса",
            "lft" =>  "491",
            "rgt" =>  "492",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "261",
            "status" =>  "10",
            "name" =>  "Штабелевщик древесины",
            "lft" =>  "493",
            "rgt" =>  "494",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "262",
            "status" =>  "10",
            "name" =>  "Акушер",
            "lft" =>  "497",
            "rgt" =>  "498",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "263",
            "status" =>  "10",
            "name" =>  "Акушер-гинеколог",
            "lft" =>  "499",
            "rgt" =>  "500",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "264",
            "status" =>  "10",
            "name" =>  "Анестезиолог",
            "lft" =>  "501",
            "rgt" =>  "502",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "265",
            "status" =>  "10",
            "name" =>  "Ветеринар",
            "lft" =>  "503",
            "rgt" =>  "504",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "266",
            "status" =>  "10",
            "name" =>  "Врач",
            "lft" =>  "505",
            "rgt" =>  "506",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "267",
            "status" =>  "10",
            "name" =>  "Гастроэнтеролог",
            "lft" =>  "507",
            "rgt" =>  "508",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "268",
            "status" =>  "10",
            "name" =>  "Дантист",
            "lft" =>  "509",
            "rgt" =>  "510",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "269",
            "status" =>  "10",
            "name" =>  "Дерматолог",
            "lft" =>  "511",
            "rgt" =>  "512",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "270",
            "status" =>  "10",
            "name" =>  "Зубной техник",
            "lft" =>  "513",
            "rgt" =>  "514",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "271",
            "status" =>  "10",
            "name" =>  "Кардиохирург",
            "lft" =>  "515",
            "rgt" =>  "516",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "272",
            "status" =>  "10",
            "name" =>  "Лаборант",
            "lft" =>  "517",
            "rgt" =>  "518",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "273",
            "status" =>  "10",
            "name" =>  "Медбрат\/медсестра",
            "lft" =>  "519",
            "rgt" =>  "520",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "274",
            "status" =>  "10",
            "name" =>  "Невропатолог",
            "lft" =>  "521",
            "rgt" =>  "522",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "275",
            "status" =>  "10",
            "name" =>  "Опекун",
            "lft" =>  "523",
            "rgt" =>  "524",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "276",
            "status" =>  "10",
            "name" =>  "Офтальмолог",
            "lft" =>  "525",
            "rgt" =>  "526",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "277",
            "status" =>  "10",
            "name" =>  "Педиатр",
            "lft" =>  "527",
            "rgt" =>  "528",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "278",
            "status" =>  "10",
            "name" =>  "Провизор",
            "lft" =>  "529",
            "rgt" =>  "530",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "279",
            "status" =>  "10",
            "name" =>  "Психиатр",
            "lft" =>  "531",
            "rgt" =>  "532",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "280",
            "status" =>  "10",
            "name" =>  "Психолог",
            "lft" =>  "533",
            "rgt" =>  "534",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "281",
            "status" =>  "10",
            "name" =>  "Реабилитолог",
            "lft" =>  "535",
            "rgt" =>  "536",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "282",
            "status" =>  "10",
            "name" =>  "Специалист по протезам",
            "lft" =>  "537",
            "rgt" =>  "538",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "283",
            "status" =>  "10",
            "name" =>  "Стоматолог",
            "lft" =>  "539",
            "rgt" =>  "540",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "284",
            "status" =>  "10",
            "name" =>  "Техники зоопарка KRS",
            "lft" =>  "541",
            "rgt" =>  "542",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "285",
            "status" =>  "10",
            "name" =>  "Травматолог",
            "lft" =>  "543",
            "rgt" =>  "544",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "286",
            "status" =>  "10",
            "name" =>  "Фармацевт",
            "lft" =>  "545",
            "rgt" =>  "546",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "287",
            "status" =>  "10",
            "name" =>  "Фельдшер",
            "lft" =>  "547",
            "rgt" =>  "548",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "288",
            "status" =>  "10",
            "name" =>  "Хирург",
            "lft" =>  "549",
            "rgt" =>  "550",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "289",
            "status" =>  "10",
            "name" =>  "Жиловщик",
            "lft" =>  "553",
            "rgt" =>  "554",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "290",
            "status" =>  "10",
            "name" =>  "Забойщик",
            "lft" =>  "555",
            "rgt" =>  "556",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "291",
            "status" =>  "10",
            "name" =>  "Мясник",
            "lft" =>  "557",
            "rgt" =>  "558",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "292",
            "status" =>  "10",
            "name" =>  "Обвальщик мяса",
            "lft" =>  "559",
            "rgt" =>  "560",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "293",
            "status" =>  "10",
            "name" =>  "Инструктор по вождению",
            "lft" =>  "563",
            "rgt" =>  "564",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "294",
            "status" =>  "10",
            "name" =>  "Координатор",
            "lft" =>  "565",
            "rgt" =>  "566",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "295",
            "status" =>  "10",
            "name" =>  "Лаборант",
            "lft" =>  "567",
            "rgt" =>  "568",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "296",
            "status" =>  "10",
            "name" =>  "Лектор",
            "lft" =>  "569",
            "rgt" =>  "570",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "297",
            "status" =>  "10",
            "name" =>  "Педагог",
            "lft" =>  "571",
            "rgt" =>  "572",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "298",
            "status" =>  "10",
            "name" =>  "Переводчик",
            "lft" =>  "573",
            "rgt" =>  "574",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "299",
            "status" =>  "10",
            "name" =>  "Преподаватель",
            "lft" =>  "575",
            "rgt" =>  "576",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "300",
            "status" =>  "10",
            "name" =>  "Учитель",
            "lft" =>  "577",
            "rgt" =>  "578",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "301",
            "status" =>  "10",
            "name" =>  "Учитель английского языка",
            "lft" =>  "579",
            "rgt" =>  "580",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "302",
            "status" =>  "10",
            "name" =>  "Учитель иностранных языков",
            "lft" =>  "581",
            "rgt" =>  "582",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "303",
            "status" =>  "10",
            "name" =>  "Учитель математики и информатики",
            "lft" =>  "583",
            "rgt" =>  "584",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "304",
            "status" =>  "10",
            "name" =>  "Филолог",
            "lft" =>  "585",
            "rgt" =>  "586",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "305",
            "status" =>  "10",
            "name" =>  "Оператор послепечатного оборудования",
            "lft" =>  "589",
            "rgt" =>  "590",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "306",
            "status" =>  "10",
            "name" =>  "Печатник",
            "lft" =>  "591",
            "rgt" =>  "592",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "307",
            "status" =>  "10",
            "name" =>  "Помощник печатника",
            "lft" =>  "593",
            "rgt" =>  "594",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "308",
            "status" =>  "10",
            "name" =>  "Работник типографии",
            "lft" =>  "595",
            "rgt" =>  "596",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "309",
            "status" =>  "10",
            "name" =>  "Резчик",
            "lft" =>  "597",
            "rgt" =>  "598",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "310",
            "status" =>  "10",
            "name" =>  "Упаковщик",
            "lft" =>  "599",
            "rgt" =>  "600",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "311",
            "status" =>  "10",
            "name" =>  "HR-менеджер",
            "lft" =>  "603",
            "rgt" =>  "604",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "312",
            "status" =>  "10",
            "name" =>  "SEO-специалист",
            "lft" =>  "605",
            "rgt" =>  "606",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "313",
            "status" =>  "10",
            "name" =>  "Web-аналитик",
            "lft" =>  "607",
            "rgt" =>  "608",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "314",
            "status" =>  "10",
            "name" =>  "Web-дизайнер",
            "lft" =>  "609",
            "rgt" =>  "610",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "315",
            "status" =>  "10",
            "name" =>  "Web-мастер",
            "lft" =>  "611",
            "rgt" =>  "612",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "316",
            "status" =>  "10",
            "name" =>  "Верстальщик",
            "lft" =>  "613",
            "rgt" =>  "614",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "317",
            "status" =>  "10",
            "name" =>  "Видео дизайнер",
            "lft" =>  "615",
            "rgt" =>  "616",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "318",
            "status" =>  "10",
            "name" =>  "Видеомонтажник",
            "lft" =>  "617",
            "rgt" =>  "618",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "319",
            "status" =>  "10",
            "name" =>  "Графический дизайнер",
            "lft" =>  "619",
            "rgt" =>  "620",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "320",
            "status" =>  "10",
            "name" =>  "Дизайнер интерфейсов",
            "lft" =>  "621",
            "rgt" =>  "622",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "321",
            "status" =>  "10",
            "name" =>  "Дизайнер интерьера",
            "lft" =>  "623",
            "rgt" =>  "624",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "322",
            "status" =>  "10",
            "name" =>  "Дизайнер мебели",
            "lft" =>  "625",
            "rgt" =>  "626",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "323",
            "status" =>  "10",
            "name" =>  "Дизайнер одежды",
            "lft" =>  "627",
            "rgt" =>  "628",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "324",
            "status" =>  "10",
            "name" =>  "Директор",
            "lft" =>  "629",
            "rgt" =>  "630",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "325",
            "status" =>  "10",
            "name" =>  "Диспетчер",
            "lft" =>  "631",
            "rgt" =>  "632",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "326",
            "status" =>  "10",
            "name" =>  "Иллюстратор",
            "lft" =>  "633",
            "rgt" =>  "634",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "327",
            "status" =>  "10",
            "name" =>  "Интернет-маркетолог",
            "lft" =>  "635",
            "rgt" =>  "636",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "328",
            "status" =>  "10",
            "name" =>  "Координатор",
            "lft" =>  "637",
            "rgt" =>  "638",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "329",
            "status" =>  "10",
            "name" =>  "Ландшафтный дизайнер",
            "lft" =>  "639",
            "rgt" =>  "640",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "330",
            "status" =>  "10",
            "name" =>  "Логист",
            "lft" =>  "641",
            "rgt" =>  "642",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "331",
            "status" =>  "10",
            "name" =>  "Менеджер",
            "lft" =>  "643",
            "rgt" =>  "644",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "332",
            "status" =>  "10",
            "name" =>  "Менеджер по продажам",
            "lft" =>  "645",
            "rgt" =>  "646",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "333",
            "status" =>  "10",
            "name" =>  "Менеджер торгового зала",
            "lft" =>  "647",
            "rgt" =>  "648",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "334",
            "status" =>  "10",
            "name" =>  "Оператор Call-центра",
            "lft" =>  "649",
            "rgt" =>  "650",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "335",
            "status" =>  "10",
            "name" =>  "Офис менеджер",
            "lft" =>  "651",
            "rgt" =>  "652",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "336",
            "status" =>  "10",
            "name" =>  "Офисный работник",
            "lft" =>  "653",
            "rgt" =>  "654",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "337",
            "status" =>  "10",
            "name" =>  "Переводчик",
            "lft" =>  "655",
            "rgt" =>  "656",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "338",
            "status" =>  "10",
            "name" =>  "Программист",
            "lft" =>  "657",
            "rgt" =>  "658",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "339",
            "status" =>  "10",
            "name" =>  "Продакт-менеджер",
            "lft" =>  "659",
            "rgt" =>  "660",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "340",
            "status" =>  "10",
            "name" =>  "Рекрутер",
            "lft" =>  "661",
            "rgt" =>  "662",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "341",
            "status" =>  "10",
            "name" =>  "Репрограф",
            "lft" =>  "663",
            "rgt" =>  "664",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "342",
            "status" =>  "10",
            "name" =>  "Ретушер",
            "lft" =>  "665",
            "rgt" =>  "666",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "343",
            "status" =>  "10",
            "name" =>  "Секретарь",
            "lft" =>  "667",
            "rgt" =>  "668",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "344",
            "status" =>  "10",
            "name" =>  "Страховой агент",
            "lft" =>  "669",
            "rgt" =>  "670",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "345",
            "status" =>  "10",
            "name" =>  "Страховщик",
            "lft" =>  "671",
            "rgt" =>  "672",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "346",
            "status" =>  "10",
            "name" =>  "Таможенный брокер",
            "lft" =>  "673",
            "rgt" =>  "674",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "347",
            "status" =>  "10",
            "name" =>  "Юрист",
            "lft" =>  "675",
            "rgt" =>  "676",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "348",
            "status" =>  "10",
            "name" =>  "Директор по продажам",
            "lft" =>  "679",
            "rgt" =>  "680",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "349",
            "status" =>  "10",
            "name" =>  "Закупщик",
            "lft" =>  "681",
            "rgt" =>  "682",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "350",
            "status" =>  "10",
            "name" =>  "Кассир",
            "lft" =>  "683",
            "rgt" =>  "684",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "351",
            "status" =>  "10",
            "name" =>  "Менеджер торгового зала",
            "lft" =>  "685",
            "rgt" =>  "686",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "352",
            "status" =>  "10",
            "name" =>  "Мерчендайзер",
            "lft" =>  "687",
            "rgt" =>  "688",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "353",
            "status" =>  "10",
            "name" =>  "Приемщик товара",
            "lft" =>  "689",
            "rgt" =>  "690",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "354",
            "status" =>  "10",
            "name" =>  "Продавец",
            "lft" =>  "691",
            "rgt" =>  "692",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "355",
            "status" =>  "10",
            "name" =>  "Продавец-консультант",
            "lft" =>  "693",
            "rgt" =>  "694",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "356",
            "status" =>  "10",
            "name" =>  "Сомелье",
            "lft" =>  "695",
            "rgt" =>  "696",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "357",
            "status" =>  "10",
            "name" =>  "Товаровед",
            "lft" =>  "697",
            "rgt" =>  "698",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "358",
            "status" =>  "10",
            "name" =>  "Торговый представитель",
            "lft" =>  "699",
            "rgt" =>  "700",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "359",
            "status" =>  "10",
            "name" =>  "Упаковщик",
            "lft" =>  "701",
            "rgt" =>  "702",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "360",
            "status" =>  "10",
            "name" =>  "Агроном",
            "lft" =>  "705",
            "rgt" =>  "706",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "361",
            "status" =>  "10",
            "name" =>  "Животновод",
            "lft" =>  "707",
            "rgt" =>  "708",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "362",
            "status" =>  "10",
            "name" =>  "Конюх",
            "lft" =>  "709",
            "rgt" =>  "710",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "363",
            "status" =>  "10",
            "name" =>  "Оператор телескопического погрузчика",
            "lft" =>  "711",
            "rgt" =>  "712",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "364",
            "status" =>  "10",
            "name" =>  "Пчеловод",
            "lft" =>  "713",
            "rgt" =>  "714",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "365",
            "status" =>  "10",
            "name" =>  "Работник в теплицу",
            "lft" =>  "715",
            "rgt" =>  "716",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "366",
            "status" =>  "10",
            "name" =>  "Работник на поле",
            "lft" =>  "717",
            "rgt" =>  "718",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "367",
            "status" =>  "10",
            "name" =>  "Работник на ферму",
            "lft" =>  "719",
            "rgt" =>  "720",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "368",
            "status" =>  "10",
            "name" =>  "Садовник",
            "lft" =>  "721",
            "rgt" =>  "722",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "369",
            "status" =>  "10",
            "name" =>  "Сборщик фруктов\/овощей",
            "lft" =>  "723",
            "rgt" =>  "724",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "370",
            "status" =>  "10",
            "name" =>  "Сельхозрабочий",
            "lft" =>  "725",
            "rgt" =>  "726",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "371",
            "status" =>  "10",
            "name" =>  "Тракторист",
            "lft" =>  "727",
            "rgt" =>  "728",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "372",
            "status" =>  "10",
            "name" =>  "Фермер",
            "lft" =>  "729",
            "rgt" =>  "730",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "373",
            "status" =>  "10",
            "name" =>  "Флорист",
            "lft" =>  "731",
            "rgt" =>  "732",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "374",
            "status" =>  "10",
            "name" =>  "Грузчик",
            "lft" =>  "735",
            "rgt" =>  "736",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "375",
            "status" =>  "10",
            "name" =>  "Кладовщик",
            "lft" =>  "737",
            "rgt" =>  "738",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "376",
            "status" =>  "10",
            "name" =>  "Оператор вилочного погрузчика\/Карщик",
            "lft" =>  "739",
            "rgt" =>  "740",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "377",
            "status" =>  "10",
            "name" =>  "Оператор телескопического погрузчика",
            "lft" =>  "741",
            "rgt" =>  "742",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "378",
            "status" =>  "10",
            "name" =>  "Работник склада",
            "lft" =>  "743",
            "rgt" =>  "744",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "379",
            "status" =>  "10",
            "name" =>  "Акробат",
            "lft" =>  "747",
            "rgt" =>  "748",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "380",
            "status" =>  "10",
            "name" =>  "Актер",
            "lft" =>  "749",
            "rgt" =>  "750",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "381",
            "status" =>  "10",
            "name" =>  "Аниматор",
            "lft" =>  "751",
            "rgt" =>  "752",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "382",
            "status" =>  "10",
            "name" =>  "Ведущий",
            "lft" =>  "753",
            "rgt" =>  "754",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "383",
            "status" =>  "10",
            "name" =>  "Вокалистка",
            "lft" =>  "755",
            "rgt" =>  "756",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "384",
            "status" =>  "10",
            "name" =>  "Гид",
            "lft" =>  "757",
            "rgt" =>  "758",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "385",
            "status" =>  "10",
            "name" =>  "Гример",
            "lft" =>  "759",
            "rgt" =>  "760",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "386",
            "status" =>  "10",
            "name" =>  "Диджей",
            "lft" =>  "761",
            "rgt" =>  "762",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "387",
            "status" =>  "10",
            "name" =>  "Звукооператор",
            "lft" =>  "763",
            "rgt" =>  "764",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "388",
            "status" =>  "10",
            "name" =>  "Звукорежиссер",
            "lft" =>  "765",
            "rgt" =>  "766",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "389",
            "status" =>  "10",
            "name" =>  "Каскадер",
            "lft" =>  "767",
            "rgt" =>  "768",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "390",
            "status" =>  "10",
            "name" =>  "Кинолог",
            "lft" =>  "769",
            "rgt" =>  "770",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "391",
            "status" =>  "10",
            "name" =>  "Киномеханик",
            "lft" =>  "771",
            "rgt" =>  "772",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "392",
            "status" =>  "10",
            "name" =>  "Кинооператор",
            "lft" =>  "773",
            "rgt" =>  "774",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "393",
            "status" =>  "10",
            "name" =>  "Кинопродюсер",
            "lft" =>  "775",
            "rgt" =>  "776",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "394",
            "status" =>  "10",
            "name" =>  "Костюмер",
            "lft" =>  "777",
            "rgt" =>  "778",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "395",
            "status" =>  "10",
            "name" =>  "Модель",
            "lft" =>  "779",
            "rgt" =>  "780",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "396",
            "status" =>  "10",
            "name" =>  "Музыкант",
            "lft" =>  "781",
            "rgt" =>  "782",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "397",
            "status" =>  "10",
            "name" =>  "Режиссер-постановщик",
            "lft" =>  "783",
            "rgt" =>  "784",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "398",
            "status" =>  "10",
            "name" =>  "Режиссер монтажа",
            "lft" =>  "785",
            "rgt" =>  "786",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "399",
            "status" =>  "10",
            "name" =>  "Тамада",
            "lft" =>  "787",
            "rgt" =>  "788",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "400",
            "status" =>  "10",
            "name" =>  "Танцовщица",
            "lft" =>  "789",
            "rgt" =>  "790",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "401",
            "status" =>  "10",
            "name" =>  "Танцор",
            "lft" =>  "791",
            "rgt" =>  "792",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "402",
            "status" =>  "10",
            "name" =>  "Техники зоопарка KRS",
            "lft" =>  "793",
            "rgt" =>  "794",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "403",
            "status" =>  "10",
            "name" =>  "Фотограф",
            "lft" =>  "795",
            "rgt" =>  "796",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "404",
            "status" =>  "10",
            "name" =>  "Фотомодель",
            "lft" =>  "797",
            "rgt" =>  "798",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "405",
            "status" =>  "10",
            "name" =>  "Хореограф",
            "lft" =>  "799",
            "rgt" =>  "800",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "406",
            "status" =>  "10",
            "name" =>  "Художник",
            "lft" =>  "801",
            "rgt" =>  "802",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "407",
            "status" =>  "10",
            "name" =>  "Вальцовщик",
            "lft" =>  "805",
            "rgt" =>  "806",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "408",
            "status" =>  "10",
            "name" =>  "Газорезчик",
            "lft" =>  "807",
            "rgt" =>  "808",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "409",
            "status" =>  "10",
            "name" =>  "Гальваник",
            "lft" =>  "809",
            "rgt" =>  "810",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "410",
            "status" =>  "10",
            "name" =>  "Доменщик",
            "lft" =>  "811",
            "rgt" =>  "812",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "411",
            "status" =>  "10",
            "name" =>  "Инженер-металлург",
            "lft" =>  "813",
            "rgt" =>  "814",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "412",
            "status" =>  "10",
            "name" =>  "Машинист компрессорных установок",
            "lft" =>  "815",
            "rgt" =>  "816",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "413",
            "status" =>  "10",
            "name" =>  "Машинист котла",
            "lft" =>  "817",
            "rgt" =>  "818",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "414",
            "status" =>  "10",
            "name" =>  "Машинист турбины",
            "lft" =>  "819",
            "rgt" =>  "820",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "415",
            "status" =>  "10",
            "name" =>  "Металлург",
            "lft" =>  "821",
            "rgt" =>  "822",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "416",
            "status" =>  "10",
            "name" =>  "Помощник сварщика",
            "lft" =>  "823",
            "rgt" =>  "824",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "417",
            "status" =>  "10",
            "name" =>  "Слесарь",
            "lft" =>  "825",
            "rgt" =>  "826",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "418",
            "status" =>  "10",
            "name" =>  "Сталевар",
            "lft" =>  "827",
            "rgt" =>  "828",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "419",
            "status" =>  "10",
            "name" =>  "Стеклодув",
            "lft" =>  "829",
            "rgt" =>  "830",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "420",
            "status" =>  "10",
            "name" =>  "Технолог-химик",
            "lft" =>  "831",
            "rgt" =>  "832",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "421",
            "status" =>  "10",
            "name" =>  "Шахтер",
            "lft" =>  "833",
            "rgt" =>  "834",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "422",
            "status" =>  "10",
            "name" =>  "Адвокат",
            "lft" =>  "837",
            "rgt" =>  "838",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "423",
            "status" =>  "10",
            "name" =>  "Банкир",
            "lft" =>  "839",
            "rgt" =>  "840",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "424",
            "status" =>  "10",
            "name" =>  "Бухгалтер",
            "lft" =>  "841",
            "rgt" =>  "842",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "425",
            "status" =>  "10",
            "name" =>  "Менеджер по продажам",
            "lft" =>  "843",
            "rgt" =>  "844",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "426",
            "status" =>  "10",
            "name" =>  "Нотариус",
            "lft" =>  "845",
            "rgt" =>  "846",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "427",
            "status" =>  "10",
            "name" =>  "Финансист",
            "lft" =>  "847",
            "rgt" =>  "848",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "428",
            "status" =>  "10",
            "name" =>  "Финансовый консультант",
            "lft" =>  "849",
            "rgt" =>  "850",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "429",
            "status" =>  "10",
            "name" =>  "Экономист",
            "lft" =>  "851",
            "rgt" =>  "852",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "430",
            "status" =>  "10",
            "name" =>  "Юрист",
            "lft" =>  "853",
            "rgt" =>  "854",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "431",
            "status" =>  "10",
            "name" =>  "Газоэлектросварщик",
            "lft" =>  "857",
            "rgt" =>  "858",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "432",
            "status" =>  "10",
            "name" =>  "Инженер-электрик",
            "lft" =>  "859",
            "rgt" =>  "860",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "433",
            "status" =>  "10",
            "name" =>  "Монтажник систем кондиционирования",
            "lft" =>  "861",
            "rgt" =>  "862",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "434",
            "status" =>  "10",
            "name" =>  "Электрик",
            "lft" =>  "863",
            "rgt" =>  "864",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "435",
            "status" =>  "10",
            "name" =>  "Электромонтажник",
            "lft" =>  "865",
            "rgt" =>  "866",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "436",
            "status" =>  "10",
            "name" =>  "Электромонтер",
            "lft" =>  "867",
            "rgt" =>  "868",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "437",
            "status" =>  "10",
            "name" =>  "Электросварщик",
            "lft" =>  "869",
            "rgt" =>  "870",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "438",
            "status" =>  "10",
            "name" =>  "Электрослесарь",
            "lft" =>  "871",
            "rgt" =>  "872",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "439",
            "status" =>  "10",
            "name" =>  "Энергетик",
            "lft" =>  "873",
            "rgt" =>  "874",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "440",
            "status" =>  "10",
            "name" =>  "QA Engineer",
            "lft" =>  "877",
            "rgt" =>  "878",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
        $this->insert('category_job', [
            "id" =>  "441",
            "status" =>  "10",
            "name" =>  "Лаборант химического анализа",
            "lft" =>  "879",
            "rgt" =>  "880",
            "depth" =>  "2",
            "meta_keywords" =>  null,
            "meta_description" =>  null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category_job}}');
    }
}
