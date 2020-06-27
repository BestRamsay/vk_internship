# vk_internship

![plantUML](http://www.plantuml.com/plantuml/png/ZLLHR-D447xthrYTxY5HdHoyGXNTWNWuyN2y8xHouuspkiIsx7Nl85Hg6WuE1T5NX8Ju2sv8Q9esvI_i_YE-sNLgjmHT7-BCpdpppJSp6-Tvhe9C5UEH5wIITKTYeBhVzw9WBFYBhrvw_IIEHL_99EOmLPQCM8SmdHu3tYFS8m95skdzb-a1A9FH4EdMEGvU2y_puaG9ffAK9GDsvsVUOIw_4vzy_55F_wvlzTPCpPavDJEzCMVwrfmoCuNxKYzrgLVcZEcjBfc-qQLvhzSuh1Y-_ZAd06qeiaJI5CcpcjXCcVd1ecR0bKXUTvd-GzyIDntBYeECVopL6ZBUuNi9gwe9x0gn1JsfwWAaoBxIrpZLnLnhCHvTzLO11afTuhC1x1a5dzM8HlE_xGHidPnBLB-mSlzsdKFHkQLR8Be4cPDAymBqYY43PKWvAAnZ0mDzwNLT1YUNYkQH-vDhXjfiv9ivQfJcrwgc6uIPimg-DNFpZb761LAlwTHbdeX3XWq_NFIUA_V1ZJib25qZU4wzjsnJ9GIKS9OFxaJxRXD-pQs2ev7Wmo0VygXQXa_Jsw24BGE66_H68wOvrSskp7lM48bENJtAMrIRW6LccD3F6F2AOGmtPaQh0oVDgBfFPjxSy9yqyss5tN_F5hQ_DJLcVeJhdBavc5_eFb0ObJ5pVAuQp6i7ezLl6YfqMUjexgS_2lASxTuAxF1GnafaWw0lUhs9vntObuYTMWkc4ZvxBRvjUmVqU-Mv4cbh8ACYWpzDaCfVx8pF0AAC8dVPE4RoMF0YXUcTx2hJksdY7JpbdxzwzUNBBxv-8iCdttpQTD3QuFfVBPEJHwaXM5CF7BKY76f9UwufJQAokLNHSbH-9S3hH49n6Q8rQv7c5iMGbWb8TJNXuUam2tBHhlcmZ5QCQCDr78nao85sQSJcirY8q9vvN8oFH0Py43fFvcgTtDjdBHvz9lUdOtlmsK36CX-2w5YADxmndQPIDBVJLUGeHMgOpsJOhWEMd1u-Eqg2h1aIRsMkk2jy3y9gZ1fcIH4Dzw8QCCWDHjmkjC8ylQDAWmpYd11hmyna73NlMlr7Cx6p1gBeAsuvw7w-oQGIZeAWFYCJVcmmhDqqv-TO6lvP_mK0)


Представленная схема была составлена c **UML**, точнее диаграммы классов. На ней изображены как *открытые* методы, так и *приватные* с их атрибутами. 
Изображены 4 класса(считая API):connection_control, game_control, game_make, API 1 интерфейс: game_API.

## Справка о репозитории ##

Этот репозиторий представляет собой бэкенд шахматной партии. Два игрока(или партия с сами собой) могут подключится к серверу и использовать API для создания партии или подключения подключения к чужой партии. Далее только два идентифицированных игрока могут вносить изменения в игру(файл игры), то есть играть друг с другом в шахматы. 

## Приницип работы ##

Пользователь создает новую игру, использую методы из **API**:

	$api = new API();
	$api -> start_game(NEW_GAME,key)

После чего создается файл игры, информационный файл прилагающийся к каждой игре и делается запись соответствия ключа генерации и номера самой игры. Второй пользователь может подключится к имеющийся игре, зная ключ генерации:

	$api -> start_game(EXIST,key)

При этом можно сыграть с самим собой, выполнив этот же метод. После этого информационный файл заполняется вторым пользователем и подключиться к этой игре уже невозможно. 

Идентификация производится в информационном файле каждой игры, где есть ID игроков и очередь их хода, у юзеров оно хранится в куки: COOKIE["id"]. Также проверяется валидность ключа, по которому можно подключится к игре или создать новую, для безопасности ключ у игроков хранится в виде хеша в куки: COOKIE["hash"]. Длительность жизни куки: ***90 минут***.

Хранение самой игры происходит на *сервере*, у игроков есть лишь виртуальные "доски" с отображением хода игры.

Теперь два игрока(или один) могут выпонять ход, только в свою очередь, также используя методы API: 

	$api -> make_step(figure, start, finish) 
Пройдя все проверки на корректность, изменения будут внесены и на сервер и отобразяться у другого игрока. 

Получить справку о игре может кто угодно, зная ключ генерации: 

	$api -> status_game() 	

Полученная информация:

1. ID игрока белых
2. ID игрока черных
3. Чья очередь ходить  


## Формат записи хода ##

	$api -> make_step(figure, start, finish)

1. figure
Для записи фигуры достаточно написать первую букву английского названия:

* Король => **k**ing => буква **k**
* Ферзь(королева) => **q**ueen => буква **q**
* Ладья => **c**astle => буква **c**
* Конь => knight, но в нашем случае возьмём дословный перевод **h**ourse =>буква **h**
* Слон => **b**ishop =>буква **b**
* Пешка => **p**awn =>буква **p**

2. start
Стартовая точка, где фигура находится в данный момент записывается в формате **LN**, где L - буква латинского алфавита от **a** до **h**, а N - число от **1** до **8**.

3. finish
Конечная точко, где окажется фигура после хода. Записывается в том формате, что и start - **LN**.


## Особенности конца игры ##

**По причине отсутствия установки конца игры виде мата, конец определяется сдачей одного из игроков, посредством специальной API функции**

	$api -> give_up(OFFER);

Далее происходит вывод всего хода игры в на экран пользователей в формате шахматной записной книги, ***однако, пользователь может убрать визуализацию данных и по своему усмотрение использоватать записи о ходе игры***. 

## Внимание ##

После начала игры, когда в партии появились 2 игрока. Перезагружать страницу нельзя, иначе игра распадется.
Играть можно повторными вызовами функции

	$api -> make_step(figure, start, finish)

<!--author: a.ta.2000@mail.ru -->