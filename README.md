# vk_internship

![plantUML](http://www.plantuml.com/plantuml/png/XLNVRZf55BxVfpZvnOjj2lpqLXlIQhnelAZtncockmDiM7R9xb2jhKafLcimzjQO65zXYs0fXVeACs_aDpFGdIAD5uKvVyxtdVETwN1O29QBGI_nsgp7WwClZwcUn-sEg3UfnrBMva4XUD-93le8XLcQyb34MHeC2fvxZesZoBE46WblYSRbVtCTM3guR1Ys1cc2JQtd6fRH9TYLj6dQE7kioptFIpF1IMHzobhquYVle8Y_unz_z65J_YwVvKeDrRMwKYEvKDVoMLsH6iBz8AUob3DrJN8bIv9BMQfREOSn8tpzfQwGjD2HAOg6A1vLm6f8wWUJDK9UYU9vW-GVyXdOnYmt63ZyOw3cQEC6trESBAVEdI4sCPzWdG0KrO_o2LP5PaTByT4Gtmga0LAM-5iWxRqElgyodE5_spImikqyWFtUjFktdHmTtHcu2Q9JWDbMjLv8VIHKW4Pt3WZZME20kUIyee5bIp4yQd-om-WndNer1aUfVbrpMY7Kc6mwLZHMDwGhxb7wfAq6UJoD21lUNlIEc_Y_6xUT8FI4u9sUtJT3bMYWXBFSkXDxBvkeLTY2dIOywB2Y4xJNowXfzHQWC3H0M68s8p5qgeQTgLjo5ohdXi_KJUmcz4cDeD3F47X6a66fHcHqNsgDrlT9ZTqD_warNzdSD-xPnCmtruEf7-6w8wk3-iNS1uJ13Ctnz-WWpqsQMVt2wKAMJX_EViA45GMzF24Ntlu7mMSd9rySV_xLkpXwz_KdhaChg5tUldcQZ9W-ZeBNgClFzxnz_1lRvyDlnUr13dy_YrD1Hvl3fqZI5OF2LkzvtlURBdRSZmjmhS-1uVHjSKtJNQ2otkOYY2FWc9Fkq4SCPfXpD6PPu0dwdPmL784D7bJmKqGTrnbBuYX0jYtJQ3LAEO-C7QI3tYdFaSyYwyaDLpK1lNf03p0mpriivCscLhRWmce33NIpTXJVgTzwZxSXtjR2mN3VtjUi1C5SOkEeXjUCPcIgKHmvgfXgn-x6QJieM8kBSzSTTdZOxJ5Xbbe_Y_atQw9TEx3BESrO7lckjWXrM0e9-raHQo4SUQfV2inLRyL9OW7i7Ib4FWY52scMQmM0e3PWfRT6jG3ZipVMtSeXhWX-V_y5)

/*In progress*/

Представленная схема была составлена c **UML**, точнее диаграммы классов. На ней изображены как *открытые* методы, так и *приватные* с их атрибутами. 
Изображены 4 класса(считая API):[connection_control](#c_c), [game_control](#g_c), [game_make](#g_m), [game_API](#g_A) и 2 интерфейса: [manage_step](#m_s), [connection_user](#c_u)

## Справка о репозитории ##

Этот репозиторий представляет собой бэкенд шахматной партии. Два игрока(или партия с сами собой) могут подключится к серверу и использовать API для создания партии или подключения подключения к чужой партии. Далее только два идентифицированных игрока могут вносить изменения в игру(файл игры), то есть играть друг с другом в шахматы. 

## Приницип работы ##

Пользователь создает новую игру, использую методы из **API**:

	control_start(NEW_GAME,key)

После чего создается файл игры, информационный файл прилагающийся к каждой игре и делается запись соответствия ключа генерации и номера самой игры. Второй пользователь может подключится к имеющийся игре, зная ключ генерации:

	control_start(EXIST,key)

При этом можно сыграть с самим собой, выполнив этот же метод. После этого информационный файл заполняется вторым пользователем и подключиться к этой игре уже невозможно. 

Идентификация производится в информационном файле каждой игры, где есть ID игроков и очередь их хода, у юзеров оно хранится в куки: COOKIE["id"]. Также проверяется валидность ключа, по которому можно подключится к игре или создать новую, для безопасности ключ у игроков хранится в виде хеша в куки: COOKIE["hash"].

Хранение самой игры происходит на *сервере*, у игроков есть лишь виртуальные "доски" с отображением хода игры.

Теперь два игрока(или один) могут выпонять ход, только в свою очередь, также используя методы API: set_step(figure, start, finish). Пройдя все проверки на корректность, изменения будут внесены и на сервер и отобразяться у другого игрока. 

Получить справку о игре может кто угодно, зная ключ генерации: 

	get_status() 	

Полученная информация:

1. ID игрока белых
2. ID игрока черных
3. Чья очередь ходить  