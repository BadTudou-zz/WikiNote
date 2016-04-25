WikiNote是一个在线协同笔记编写与笔记分享平台，其运行方式类似于维基百科。

模式：BS(Brower Server)
前端：HTML+CSS3+JavaScript/JQuery
后台：PHP7 + MySQL
通信：JSON(AJAX技术)

一.功能模块
1.用户	
2.书籍	x
3.笔记
4.系统

1.用户
	a.注册
	2.登录
	3.注销
	4.修改信息

2.书籍	x
	a.添加
	b.修改
	c.删除

3.笔记
	a.添加
	b.修改
	c.删除

4.系统
	a.用户：添加，删除，修改
	b.笔记(类型)：添加, 删除, 修改

4.系统
二.数据设计
1.用户
	a.user	：uID, pwd, nickname
	b.manager:mID, password

2.笔记
	a.note 	: nID, notetypeID(b.type), title, creatorID, createTime, modifyTime, filepath
	g.author: nId, uID
	

3.书籍 x

4.系统
	b.type: typeID, p1ID, p2ID, p3ID, p4ID

	c.p1  	: p1ID, name
	d.p2
	e.p3
	f.p4

	h.log 	: logID, uID, nID, replaceTime, logPath
	i.msg 	: msgID, type, text
	j.usermsg: uID, msgID
