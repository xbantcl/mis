create table `mis_user` (
    `uid` int(4) not null auto_increment,
    `username` varchar(15) not null,
    `department` varchar(15) not null,
    `email` varchar(20) not null,
    `admin` bool not null,
    PRIMARY KEY  (`uid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

create table `mis_product` (
    `pid` int(4) not null auto_increment,
    `project` varchar(35),
    `prdc_name` varchar(20) not null,
    `prdc_price` float(4),
    `prdc_count` int(4) not null,
    `status` tinyint not null,
    `comments` varchar(100),
    `add_date` date not null,
    `total` int(4),
    `comefrom` varchar(35),
    PRIMARY KEY(`pid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

create table `mis_borrow_info`(
    `bid` int(4) not null auto_increment,
    `uid` int(4) not null,
    `project` varchar(35),
    `prdc_name` varchar(20) not null,
    `prdc_count` varchar(20) not null,
    `status` tinyint not null,
    `borrow_date` varchar(20),
    PRIMARY KEY(`bid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

create table `mis_history` (
    `hid` int(4) not null auto_increment,
    `uid` int(4) not null,
    `project` varchar(35),
    `prdc_name` varchar(20) not null,
    `prdc_count` varchar(20) not null,
    `status` tinyint not null,
    `borrow_date` varchar(20),
    `back_date` varchar(20),
    `comments` varchar(100),
    PRIMARY KEY(`hid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

create table `mis_out_info`(
    `oid` int(4) not null auto_increment,
    `project` varchar(35),
    `prdc_name` varchar(20) not null,
    `prdc_count` varchar(20) not null,
    `status` tinyint not null,
    `borrow_date` varchar(20),
    `destination` varchar(35),
    PRIMARY KEY(`oid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

create table `mis_out_history`(
    `hid` int(4) not null auto_increment,
    `project` varchar(35),
    `prdc_name` varchar(20) not null,
    `prdc_count` varchar(20) not null,
    `status` tinyint not null,
    `borrow_date` varchar(20),
    `back_date` varchar(20),
    `comments` varchar(100),
    PRIMARY KEY(`hid`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
