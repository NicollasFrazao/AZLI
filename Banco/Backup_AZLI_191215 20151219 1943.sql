-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.41


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema db_azli
--

CREATE DATABASE IF NOT EXISTS db_azli;
USE db_azli;

--
-- Definition of table `defeito_aparelho`
--

DROP TABLE IF EXISTS `defeito_aparelho`;
CREATE TABLE `defeito_aparelho` (
  `cd_defeito` int(11) NOT NULL,
  `cd_item_os` int(11) NOT NULL,
  KEY `fk_defeito_defeito_aparelho` (`cd_defeito`),
  KEY `fk_defeito_item_os` (`cd_item_os`),
  CONSTRAINT `fk_defeito_defeito_aparelho` FOREIGN KEY (`cd_defeito`) REFERENCES `tb_defeito` (`cd_defeito`),
  CONSTRAINT `fk_defeito_item_os` FOREIGN KEY (`cd_item_os`) REFERENCES `item_os` (`cd_item_os`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `defeito_aparelho`
--

/*!40000 ALTER TABLE `defeito_aparelho` DISABLE KEYS */;
/*!40000 ALTER TABLE `defeito_aparelho` ENABLE KEYS */;


--
-- Definition of table `item_os`
--

DROP TABLE IF EXISTS `item_os`;
CREATE TABLE `item_os` (
  `cd_item_os` int(11) NOT NULL AUTO_INCREMENT,
  `ds_item_os` varchar(500) DEFAULT NULL,
  `cd_aparelho` int(11) NOT NULL,
  `cd_os` int(11) NOT NULL,
  PRIMARY KEY (`cd_aparelho`,`cd_os`),
  UNIQUE KEY `cd_item_os` (`cd_item_os`),
  KEY `fk_aparelho_os_aparelho` (`cd_aparelho`),
  KEY `fk_aparelho_os_os` (`cd_os`),
  CONSTRAINT `fk_aparelho_os_aparelho` FOREIGN KEY (`cd_aparelho`) REFERENCES `tb_aparelho` (`cd_aparelho`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_aparelho_os_os` FOREIGN KEY (`cd_os`) REFERENCES `tb_os` (`cd_os`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_os`
--

/*!40000 ALTER TABLE `item_os` DISABLE KEYS */;
INSERT INTO `item_os` (`cd_item_os`,`ds_item_os`,`cd_aparelho`,`cd_os`) VALUES 
 (1,NULL,1,2),
 (2,'Deu merda...',1,3),
 (8,'Segura teu outro filho aí, Edu',1,4),
 (13,'Meu merda...',1,7),
 (23,'Taca fogo antes que ponha ovos',1,8),
 (134,'',1,9),
 (135,'',1,10),
 (136,'',1,11),
 (137,'',1,12),
 (138,'',1,13),
 (139,'',1,14),
 (140,'',1,15),
 (141,'',1,16),
 (142,'',1,17),
 (9,'Chegaaaaaaaaaaaa k7',2,4),
 (30,'Quero trocar logooooooooooo',33,8),
 (144,'',42,18);
/*!40000 ALTER TABLE `item_os` ENABLE KEYS */;


--
-- Definition of table `tb_aparelho`
--

DROP TABLE IF EXISTS `tb_aparelho`;
CREATE TABLE `tb_aparelho` (
  `cd_aparelho` int(11) NOT NULL AUTO_INCREMENT,
  `cd_imei` varchar(15) DEFAULT NULL,
  `cd_venda` tinyint(1) DEFAULT NULL,
  `ds_aparelho` varchar(500) DEFAULT NULL,
  `cd_modelo` int(11) DEFAULT NULL,
  `cd_cliente` int(11) DEFAULT NULL,
  `dt_cadastro` datetime DEFAULT NULL,
  `cd_imei_secundario` varchar(15) DEFAULT NULL,
  `cd_numero_serie` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cd_aparelho`),
  UNIQUE KEY `cd_imei_secundario` (`cd_imei_secundario`),
  UNIQUE KEY `cd_numero_serie` (`cd_numero_serie`),
  UNIQUE KEY `uk_imei` (`cd_imei`) USING HASH,
  KEY `fk_aparelho_modelo` (`cd_modelo`),
  KEY `fk_aparelho_cliente` (`cd_cliente`),
  CONSTRAINT `fk_aparelho_cliente` FOREIGN KEY (`cd_cliente`) REFERENCES `tb_cliente` (`cd_cliente`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_aparelho_modelo` FOREIGN KEY (`cd_modelo`) REFERENCES `tb_modelo` (`cd_modelo`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_aparelho`
--

/*!40000 ALTER TABLE `tb_aparelho` DISABLE KEYS */;
INSERT INTO `tb_aparelho` (`cd_aparelho`,`cd_imei`,`cd_venda`,`ds_aparelho`,`cd_modelo`,`cd_cliente`,`dt_cadastro`,`cd_imei_secundario`,`cd_numero_serie`) VALUES 
 (1,'357268060936285',NULL,'Cor: Preto',235,1,'2015-12-21 01:02:50',NULL,NULL),
 (2,'142141414141417',NULL,'Cor: Preto/Vermelho',211,NULL,'2015-12-26 16:22:17',NULL,NULL),
 (3,'351451208401216',NULL,'Cor: Branco',211,1,'2015-12-26 16:35:37',NULL,NULL),
 (33,'355689052704799',NULL,'Bem velho, Cor: vermlho',89,5,'2016-01-27 03:01:41','355690052704797',NULL),
 (38,NULL,NULL,'Muito lento',463,1,'2016-01-27 03:29:47',NULL,'42141412414'),
 (41,NULL,NULL,'Aparelho prata',450,9,'2016-01-27 11:03:39',NULL,'32124322332'),
 (42,NULL,NULL,'',463,1,'2016-02-21 17:00:26',NULL,'H9E8R2J294U898J');
/*!40000 ALTER TABLE `tb_aparelho` ENABLE KEYS */;


--
-- Definition of table `tb_categoria`
--

DROP TABLE IF EXISTS `tb_categoria`;
CREATE TABLE `tb_categoria` (
  `cd_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nm_categoria` varchar(70) DEFAULT NULL,
  `ic_editavel` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`cd_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_categoria`
--

/*!40000 ALTER TABLE `tb_categoria` DISABLE KEYS */;
INSERT INTO `tb_categoria` (`cd_categoria`,`nm_categoria`,`ic_editavel`) VALUES 
 (1,'Smartphone',0),
 (2,'Tablet',0),
 (3,'Notebook',1),
 (4,'Receptor',1);
/*!40000 ALTER TABLE `tb_categoria` ENABLE KEYS */;


--
-- Definition of table `tb_cliente`
--

DROP TABLE IF EXISTS `tb_cliente`;
CREATE TABLE `tb_cliente` (
  `cd_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nm_cliente` varchar(120) DEFAULT NULL,
  `cd_cpf` varchar(11) DEFAULT NULL,
  `cd_rg` varchar(9) DEFAULT NULL,
  `cd_telefone1` varchar(10) DEFAULT NULL,
  `cd_telefone2` varchar(11) DEFAULT NULL,
  `nm_endereco` varchar(100) DEFAULT NULL,
  `ds_referencias` varchar(500) DEFAULT NULL,
  `dt_cadastro` datetime DEFAULT NULL,
  PRIMARY KEY (`cd_cliente`),
  UNIQUE KEY `cd_cpf` (`cd_cpf`),
  UNIQUE KEY `cd_rg` (`cd_rg`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_cliente`
--

/*!40000 ALTER TABLE `tb_cliente` DISABLE KEYS */;
INSERT INTO `tb_cliente` (`cd_cliente`,`nm_cliente`,`cd_cpf`,`cd_rg`,`cd_telefone1`,`cd_telefone2`,`nm_endereco`,`ds_referencias`,`dt_cadastro`) VALUES 
 (1,'Nícollas Leite Frazão Santos','11694862585','566608236','1335764231','13991950739','Rua Ibrahin Abdala Set El Banat, Nº50, Jardim Rio Branco, São Vicente - SP','Referências não fornecidas','2015-12-19 18:54:13'),
 (5,'Nívio Ferreira Santos','62081339471','418757896','1335764231','13991950739','Rua Ibrahin Abdala Set El Banat, Nº50, Jardim Rio Branco, São Vicente - SP','Sem referências','2016-01-15 19:45:12'),
 (6,'Leonardo Souza','44588573802','443398276','1330130958','13981275492','Rua Frei Gaspar, 1234','Vip X','2016-01-25 10:19:02'),
 (8,'Robelso','47991177858','531037514','1330223455','13981276654','Rua Lorival Moreira do Amaral','Fabrica de Vidro','2016-01-27 09:47:47'),
 (9,'Gustavo Alves De Araújo','42328816870','425036868','1332233366','13987654321','Rua Minha Rua, 19','Próximo ao meu vizinho','2016-01-27 11:01:04');
/*!40000 ALTER TABLE `tb_cliente` ENABLE KEYS */;


--
-- Definition of table `tb_defeito`
--

DROP TABLE IF EXISTS `tb_defeito`;
CREATE TABLE `tb_defeito` (
  `cd_defeito` int(11) NOT NULL AUTO_INCREMENT,
  `nm_defeito` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`cd_defeito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_defeito`
--

/*!40000 ALTER TABLE `tb_defeito` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_defeito` ENABLE KEYS */;


--
-- Definition of table `tb_estabelecimento`
--

DROP TABLE IF EXISTS `tb_estabelecimento`;
CREATE TABLE `tb_estabelecimento` (
  `cd_estabelecimento` int(11) NOT NULL AUTO_INCREMENT,
  `cd_cnpj` char(14) DEFAULT NULL,
  `cd_senha` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`cd_estabelecimento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_estabelecimento`
--

/*!40000 ALTER TABLE `tb_estabelecimento` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_estabelecimento` ENABLE KEYS */;


--
-- Definition of table `tb_fornecedor`
--

DROP TABLE IF EXISTS `tb_fornecedor`;
CREATE TABLE `tb_fornecedor` (
  `cd_fornecedor` int(11) NOT NULL AUTO_INCREMENT,
  `nm_fornecedor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cd_fornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_fornecedor`
--

/*!40000 ALTER TABLE `tb_fornecedor` DISABLE KEYS */;
INSERT INTO `tb_fornecedor` (`cd_fornecedor`,`nm_fornecedor`) VALUES 
 (1,'Fornecedor teste');
/*!40000 ALTER TABLE `tb_fornecedor` ENABLE KEYS */;


--
-- Definition of table `tb_funcionario`
--

DROP TABLE IF EXISTS `tb_funcionario`;
CREATE TABLE `tb_funcionario` (
  `cd_funcionario` int(11) NOT NULL AUTO_INCREMENT,
  `nm_funcionario` varchar(120) DEFAULT NULL,
  `cd_estabelecimento` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_funcionario`),
  KEY `fk_funcionario_estabelecimento` (`cd_estabelecimento`),
  CONSTRAINT `fk_funcionario_estabelecimento` FOREIGN KEY (`cd_estabelecimento`) REFERENCES `tb_estabelecimento` (`cd_estabelecimento`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_funcionario`
--

/*!40000 ALTER TABLE `tb_funcionario` DISABLE KEYS */;
INSERT INTO `tb_funcionario` (`cd_funcionario`,`nm_funcionario`,`cd_estabelecimento`) VALUES 
 (2,'Nícollas',NULL),
 (3,'Lucas',NULL),
 (4,'Luiz',NULL),
 (5,'Gustavo',NULL),
 (6,'João',NULL),
 (7,'Vitor',NULL),
 (8,'Edu',NULL),
 (9,'Irmão do Edu',NULL),
 (10,'Filha do Edu',NULL);
/*!40000 ALTER TABLE `tb_funcionario` ENABLE KEYS */;


--
-- Definition of table `tb_marca`
--

DROP TABLE IF EXISTS `tb_marca`;
CREATE TABLE `tb_marca` (
  `cd_marca` int(11) NOT NULL AUTO_INCREMENT,
  `nm_marca` varchar(120) DEFAULT NULL,
  `ic_editavel` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`cd_marca`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_marca`
--

/*!40000 ALTER TABLE `tb_marca` DISABLE KEYS */;
INSERT INTO `tb_marca` (`cd_marca`,`nm_marca`,`ic_editavel`) VALUES 
 (1,'Samsung',0),
 (2,'LG',0),
 (3,'Motorola',0),
 (4,'Apple',0),
 (5,'Nokia',0),
 (6,'Microsoft',0),
 (7,'Sony',0),
 (8,'Asus',0),
 (9,'Alcatel',0),
 (10,'Blu',0),
 (11,'CCE',0),
 (12,'Huawei',0),
 (13,'Multilaser',0),
 (14,'Lenovo',0),
 (15,'Positivo',0),
 (16,'Philco',0),
 (17,'Xiaomi',0);
/*!40000 ALTER TABLE `tb_marca` ENABLE KEYS */;


--
-- Definition of table `tb_modelo`
--

DROP TABLE IF EXISTS `tb_modelo`;
CREATE TABLE `tb_modelo` (
  `cd_modelo` int(11) NOT NULL AUTO_INCREMENT,
  `nm_modelo` varchar(200) DEFAULT NULL,
  `cd_marca` int(11) NOT NULL,
  `cd_categoria` int(11) DEFAULT NULL,
  `ic_editavel` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`cd_modelo`),
  KEY `fk_modelo_marca` (`cd_marca`),
  KEY `fk_modelo_categoria` (`cd_categoria`),
  CONSTRAINT `fk_modelo_categoria` FOREIGN KEY (`cd_categoria`) REFERENCES `tb_categoria` (`cd_categoria`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_modelo_marca` FOREIGN KEY (`cd_marca`) REFERENCES `tb_marca` (`cd_marca`)
) ENGINE=InnoDB AUTO_INCREMENT=464 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_modelo`
--

/*!40000 ALTER TABLE `tb_modelo` DISABLE KEYS */;
INSERT INTO `tb_modelo` (`cd_modelo`,`nm_modelo`,`cd_marca`,`cd_categoria`,`ic_editavel`) VALUES 
 (1,'Galaxy J5 J500MDS 16GB',1,1,0),
 (2,'Galaxy Gran Prime Duos G531H 8GB',1,1,0),
 (3,'Galaxy S6 G920 32GB',1,1,0),
 (4,'Galaxy J7 J700MDS 16GB',1,1,0),
 (5,'Galaxy A5 A500M 16GB',1,1,0),
 (6,'Galaxy S5 New Edition Duos SM-G903M 16GB',1,1,0),
 (7,'Galaxy J2 J200BT 8GB',1,1,0),
 (8,'Galaxy Gran Prime Duos TV G530BT 8GB',1,1,0),
 (9,'Galaxy S6 Edge G925I 32GB',1,1,0),
 (10,'Galaxy E5 E500 16GB',1,1,0),
 (11,'Galaxy On 7 SM-G600 8GB',1,1,0),
 (12,'Galaxy S6 Edge+ G928 32GB',1,1,0),
 (13,'Galaxy A7 A700 16GB',1,1,0),
 (14,'Galaxy Alpha G850M 32GB',1,1,0),
 (15,'Galaxy Gran Neo Plus Duos GT-I9060C 8GB',1,1,0),
 (16,'Galaxy S5 G900M 16GB',1,1,0),
 (17,'Galaxy Note 5 N920 32GB',1,1,0),
 (18,'Galaxy Win 2 Duos TV G360B 8GB',1,1,0),
 (19,'Galaxy Note 4 SM-N910C 32GB',1,1,0),
 (20,'Galaxy S5 Duos G900MD 16GB',1,1,0),
 (21,'Galaxy E7 E700 16GB',1,1,0),
 (22,'Galaxy A3 SM-A300M/DS 16GB',1,1,0),
 (23,'Galaxy S6 Edge G925 64GB',1,1,0),
 (24,'Galaxy S5 Mini Duos G800H 16GB',1,1,0),
 (25,'Galaxy Gran Prime Duos SMG531 8GB',1,1,0),
 (26,'Galaxy Core 2 Duos G355M 4GB',1,1,0),
 (27,'Galaxy Win 2 Duos G360M 8GB',1,1,0),
 (28,'Galaxy Gran Prime Duos G531 TV 8GB',1,1,0),
 (29,'Galaxy Ace 4 Neo Duos G318ML 4GB',1,1,0),
 (30,'Galaxy Gran Prime Duos G530H 8GB',1,1,0),
 (31,'Galaxy Ace 4 Duos G313M 4GB',1,1,0),
 (32,'Galaxy Core Plus G3502 4GB',1,1,0),
 (33,'Galaxy Pocket 2 Duos G110 4GB',1,1,0),
 (34,'Galaxy S3 Neo Duos GT-I9300I 16GB',1,1,0),
 (35,'Galaxy S4 Mini Duos GT-I9192 8GB',1,1,0),
 (36,'Galaxy Young 2 Duos G130BT 4GB',1,1,0),
 (37,'Galaxy Gran 2 Duos TV G7102 8GB',1,1,0),
 (38,'Galaxy S2 Duos TV GT-S7273T 4GB',1,1,0),
 (39,'Galaxy J1 Ace Duos J110L 4GB',1,1,0),
 (40,'Galaxy Note 3 Neo Duos SM-N7502 16GB',1,1,0),
 (41,'Galaxy Ace 4 Neo Duos G316M 4GB',1,1,0),
 (42,'Galaxy J5 J500MDS 8GB',1,1,0),
 (43,'Galaxy Gran Neo Duos GT-I9063 8GB',1,1,0),
 (44,'Ativ S I8750 16GB',1,1,0),
 (45,'Galaxy Pocket Neo Duos S5312 4GB',1,1,0),
 (46,'Galaxy Pocket Plus Duos S5303 4GB',1,1,0),
 (47,'Galaxy S3 GT-I9300 16GB',1,1,0),
 (48,'Galaxy Fame Duos S6812 4GB',1,1,0),
 (49,'Galaxy Young 2 Duos SM-G130M 4GB',1,1,0),
 (50,'Galaxy S3 Mini VE I8200 8GB',1,1,0),
 (51,'Galaxy J2 J200M 8GB',1,1,0),
 (52,'Galaxy Note Edge N915T 32GB',1,1,0),
 (53,'Galaxy Note 3 N9005 32GB',1,1,0),
 (54,'Galaxy Trend Lite Duos S7392 4GB',1,1,0),
 (55,'Galaxy S3 Slim G3812B 8GB',1,1,0),
 (56,'Galaxy S3 LTE GT-I9305 16GB',1,1,0),
 (57,'Galaxy S4 GT-I9505 16GB',1,1,0),
 (58,'Galaxy Young 2 Pro G130BU 4GB',1,1,0),
 (59,'Galaxy Ace 4 Neo Duos SM-G316ML 4GB',1,1,0),
 (60,'Galaxy S4 Value Edition I9515 16GB',1,1,0),
 (61,'Galaxy Ace 4 Lite Duos G313ML 4GB',1,1,0),
 (62,'Galaxy S5 G900H 16GB',1,1,0),
 (63,'Galaxy S Duos S7562 4GB',1,1,0),
 (64,'Galaxy J1 J100 4GB',1,1,0),
 (65,'Galaxy S4 Active GT-I9295 16GB',1,1,0),
 (66,'Corby S3650',1,1,0),
 (67,'Galaxy Y Duos S6102',1,1,0),
 (68,'Galaxy Ace Duos S6802 3GB',1,1,0),
 (69,'Omnia M S7530 4GB',1,1,0),
 (70,'Galaxy Express GT-I8730 8GB',1,1,0),
 (71,'Corby II S3850',1,1,0),
 (72,'Star S5230',1,1,0),
 (73,'Galaxy S5 G900M 32GB',1,1,0),
 (74,'Galaxy Fame Lite Duos GT-S6792 4GB',1,1,0),
 (75,'Galaxy Young 2 Duos Pro Barbie G130BU 4GB',1,1,0),
 (76,'Galaxy Win Duos GT-I8552 8GB',1,1,0),
 (77,'Galaxy Young Plus GT-S6293T 4GB',1,1,0),
 (78,'Galaxy Pocket 2 G110B 4GB',1,1,0),
 (79,'Galaxy Fame GT-S6810 4GB',1,1,0),
 (80,'Galaxy Core Plus G3502L 4GB',1,1,0),
 (81,'Galaxy K Zoom SM-C115 8GB',1,1,0),
 (82,'Galaxy Ace 3 S7275 8GB',1,1,0),
 (83,'Galaxy Fame Lite GT-S6790 4GB',1,1,0),
 (84,'Galaxy S2 I9100 16GB',1,1,0),
 (85,'Galaxy S Duos 2 S7582 4GB',1,1,0),
 (86,'Galaxy Y S5360 Android',1,1,0),
 (87,'Galaxy S4 Zoom SM-C101 8GB',1,1,0),
 (88,'Galaxy Pocket Plus S5301 4GB',1,1,0),
 (89,'Galaxy Young Duos TV GT-S6313T 4GB',1,1,0),
 (90,'Galaxy Gran Duos GT-I9082 8GB',1,1,0),
 (91,'Galaxy S3 Mini I8190 8GB',1,1,0),
 (92,'Galaxy S4 Mini GT-I9195 8GB',1,1,0),
 (93,'Galaxy Trend Lite GT-S7390 4GB',1,1,0),
 (94,'Galaxy Beam I8530 8GB',1,1,0),
 (95,'Galaxy Ace S5830',1,1,0),
 (96,'Galaxy Mega Duos GT-I9152 8GB',1,1,0),
 (97,'Galaxy S2 Lite I9070 8GB',1,1,0),
 (98,'Galaxy Note 2 N7100 16GB',1,1,0),
 (99,'Galaxy Pocket Neo S5310 4GB',1,1,0),
 (100,'Rex 60 TV C3313T',1,1,0),
 (101,'Star III Duos S5222 ',1,1,0),
 (102,'Galaxy Mega GT-I9200 8GB',1,1,0),
 (103,'Galaxy Music Duos S6012 4GB',1,1,0),
 (104,'Galaxy Note 5 N920 64GB',1,1,0),
 (105,'Galaxy Note N7000 16GB',1,1,0),
 (106,'Galaxy Pocket Duos S5302 3GB',1,1,0),
 (107,'Galaxy S3 Duos GT-I8262B 8GB',1,1,0),
 (108,'Galaxy S6 Edge+ 64GB 4G',1,1,0),
 (109,'Galaxy Star Trios GT-S5283B 4GB',1,1,0),
 (110,'Galaxy X I9250 16GB',1,1,0),
 (111,'Galaxy Y TV S5367',1,1,0),
 (112,'F60 D392 4GB 4G',2,1,0),
 (113,'G Flex 2 H955 16GB',2,1,0),
 (114,'G Flex 2 H955 32GB',2,1,0),
 (115,'G Flex D956 32GB',2,1,0),
 (116,'G Flex D958 32GB',2,1,0),
 (117,'G Pro Lite D680 8GB',2,1,0),
 (118,'G Pro Lite Dual D685 8GB',2,1,0),
 (119,'G G2 D805 16GB',2,1,0),
 (120,'G G2 D805 32GB',2,1,0),
 (121,'G G2 Lite D295 4GB',2,1,0),
 (122,'G G2 Mini Dual D618 8GB',2,1,0),
 (123,'G G3 Beat D724 8GB',2,1,0),
 (124,'G G3 D855 16GB',2,1,0),
 (125,'G G3 D855 32GB',2,1,0),
 (126,'G G3 Stylus D690 8GB',2,1,0),
 (127,'G G3 Stylus Dual D690 8GB',2,1,0),
 (128,'G G4 Beat H736 8GB',2,1,0),
 (129,'G G4 H815P 32GB',2,1,0),
 (130,'G G4 H818P 32GB',2,1,0),
 (131,'G G4 Stylus H540T 16GB',2,1,0),
 (132,'G G4 Stylus H630 16GB',2,1,0),
 (133,'Google Nexus 4 E960 16GB',2,1,0),
 (134,'Google Nexus 5 D821 16GB',2,1,0),
 (135,'Google Nexus 5X 16GB 4G',2,1,0),
 (136,'Google Nexus 5X 32GB 4G',2,1,0),
 (137,'Joy H222F 4GB',2,1,0),
 (138,'Joy H222TV 4GB',2,1,0),
 (139,'L Prime D337 8GB',2,1,0),
 (140,'L20 D100 4GB',2,1,0),
 (141,'L20 D105 4GB',2,1,0),
 (142,'L20 D107 4GB',2,1,0),
 (143,'L30 D125 4GB',2,1,0),
 (144,'L35 D157 4GB',2,1,0),
 (145,'L40 D175 4GB',2,1,0),
 (146,'L40 D180 4GB',2,1,0),
 (147,'L50 Sporty D227 4GB',2,1,0),
 (148,'L65 D285 4GB',2,1,0),
 (149,'L70 D325 4GB',2,1,0),
 (150,'L70 D340 4GB',2,1,0),
 (151,'L80 D375 8GB',2,1,0),
 (152,'L80 D385 8GB',2,1,0),
 (153,'L90 D410 8GB',2,1,0),
 (154,'Leon H326TV 8GB',2,1,0),
 (155,'Leon H342F 8GB 4G',2,1,0),
 (156,'Optimus 2X P990 8GB',2,1,0),
 (157,'Optimus 3D Max P720 8GB',2,1,0),
 (158,'Optimus 3D P920 8GB',2,1,0),
 (159,'Optimus F3 P655 4GB',2,1,0),
 (160,'Optimus F5 P875 8GB',2,1,0),
 (161,'Optimus G E977 32GB',2,1,0),
 (162,'Optimus G Pro E989 16GB',2,1,0),
 (163,'LG Optimus GT540',2,1,0),
 (164,'Optimus L1 II Dual E415 4GB',2,1,0),
 (165,'Optimus L1 II E410 4GB',2,1,0),
 (166,'Optimus L1 II Tri E475 4GB',2,1,0),
 (167,'Optimus L3 Dual E405 2GB',2,1,0),
 (168,'Optimus L3 E400 2GB',2,1,0),
 (169,'Optimus L3 II Dual E435 4GB',2,1,0),
 (170,'Optimus L3 II E425 4GB',2,1,0),
 (171,'Optimus L4 II Dual E445 4GB',2,1,0),
 (172,'Optimus L4 II Dual E467 4GB',2,1,0),
 (173,'Optimus L4 II E465 4GB',2,1,0),
 (174,'Optimus L4 II Tri Chip E470f 4GB',2,1,0),
 (175,'Optimus L5 Dual E615 4GB',2,1,0),
 (176,'Optimus L5 E612 4GB',2,1,0),
 (177,'Optimus L5 II Dual E455 4GB',2,1,0),
 (178,'Optimus L5 II E450 4GB',2,1,0),
 (179,'Optimus L7 II Dual P716 4GB',2,1,0),
 (180,'Optimus L7 II P714 4GB',2,1,0),
 (181,'Optimus L7 P705 4GB',2,1,0),
 (182,'Optimus L9 P768 4GB',2,1,0),
 (183,'Prime II X170FTV 8GB',2,1,0),
 (184,'Prime Plus H502F 8GB',2,1,0),
 (185,'Prime Plus H502TV 8GB',2,1,0),
 (186,'Prime Plus H522F 8GB',2,1,0),
 (187,'Volt H422 8GB',2,1,0),
 (188,'Volt H422TV 8GB',2,1,0),
 (189,'Volt H442F 8GB 4G',2,1,0),
 (190,'Atrix MB860 16GB',3,1,0),
 (191,'Atrix TV XT682',3,1,0),
 (192,'Atrix TV XT687',3,1,0),
 (193,'Defy MB525 2GB',3,1,0),
 (194,'Defy Mini XT320',3,1,0),
 (195,'Defy Mini XT321',3,1,0),
 (196,'Pro XT560',3,1,0),
 (197,'Fire XT317',3,1,0),
 (198,'Iron Rock XT626',3,1,0),
 (199,'Milestone 1 A853',3,1,0),
 (200,'Moto E 2ª Geração Colors XT1514 16GB',3,1,0),
 (201,'Moto E 2ª Geração DTV Colors XT1523 16GB',3,1,0),
 (202,'Moto E 2ª Geração XT1506 8GB',3,1,0),
 (203,'Moto E 2ª Geração XT1514 8GB',3,1,0),
 (204,'Moto E DTV Colors Edition XT1025 4GB',3,1,0),
 (205,'Moto E XT1021 4GB',3,1,0),
 (206,'Moto E XT1022 4GB',3,1,0),
 (207,'Moto G XT1033 Colors Edition 16GB',3,1,0),
 (208,'Moto G XT1033 Dual 8GB',3,1,0),
 (209,'Moto G 2ª Geração DTV Colors XT1069 16GB',3,1,0),
 (210,'Moto G 2ª Geração XT1068 8GB',3,1,0),
 (211,'Moto G 2ª Geração XT1078 16GB',3,1,0),
 (212,'Moto G 3ª Geração Colors XT1543 16GB',3,1,0),
 (213,'Moto G 3ª Geração DTV Colors XT1544 16GB',3,1,0),
 (214,'Moto G 3ª Geração Music XT1543 16GB',3,1,0),
 (215,'Moto G 3ª Geração Turbo XT1556 16GB',3,1,0),
 (216,'Moto G 3ª Geração XT1543 8GB',3,1,0),
 (217,'Moto G 3ª Geração XT1543 2GB RAM 16GB',3,1,0),
 (218,'Moto G XT1032 8GB',3,1,0),
 (219,'Moto G XT1039 8GB',3,1,0),
 (220,'Moto G XT1040 8GB',3,1,0),
 (221,'Moto G XT1033 Music Edition 16GB',3,1,0),
 (222,'Moto Maxx XT1225 64GB',3,1,0),
 (223,'Moto Smart XT389',3,1,0),
 (224,'Moto X 2ª Geração XT1097 32GB',3,1,0),
 (225,'Moto X Force XT1580 64GB',3,1,0),
 (226,'Moto X Play Colors XT1563 32GB',3,1,0),
 (227,'Moto X Play XT1563 16GB',3,1,0),
 (228,'Moto X Play XT1563 32GB',3,1,0),
 (229,'Moto X Style XT1572 32GB',3,1,0),
 (230,'Moto X XT1058 16GB',3,1,0),
 (231,'Moto X XT1058 32GB',3,1,0),
 (232,'MotoSmart ME XT305',3,1,0),
 (233,'Razr D1 XT915 4GB',3,1,0),
 (234,'Razr D1 XT916 4GB',3,1,0),
 (235,'Razr D1 XT918 4GB',3,1,0),
 (236,'Razr D3 XT920 4GB',3,1,0),
 (237,'Razr HD XT925 16GB',3,1,0),
 (238,'Razr I XT890 8GB',3,1,0),
 (239,'Razr XT910 16GB Android',3,1,0),
 (240,'Spice Key XT316 Android',3,1,0),
 (241,'Spice XT XT531 Android',3,1,0),
 (242,'Spice XT300 Android',3,1,0),
 (243,'XT627',3,1,0),
 (244,'iPhone 6 Plus 128GB',4,1,0),
 (245,'iPhone 6 Plus 16GB',4,1,0),
 (246,'iPhone 6 Plus 64GB',4,1,0),
 (247,'iPhone 4S 16GB',4,1,0),
 (248,'iPhone 4S 8GB',4,1,0),
 (249,'iPhone 5 16GB',4,1,0),
 (250,'iPhone 5C 16GB',4,1,0),
 (251,'iPhone 5C 32GB',4,1,0),
 (252,'iPhone 5C 8GB',4,1,0),
 (253,'iPhone 5S 16GB',4,1,0),
 (254,'iPhone 5S 32GB',4,1,0),
 (255,'iPhone 5S 64GB',4,1,0),
 (256,'iPhone 6S 128GB',4,1,0),
 (257,'iPhone 6S 16GB',4,1,0),
 (258,'iPhone 6S 64GB',4,1,0),
 (259,'iPhone 6S Plus 128GB',4,1,0),
 (260,'iPhone 6S Plus 16GB',4,1,0),
 (261,'iPhone 6S Plus 64GB',4,1,0),
 (262,'iPhone 6 128GB',4,1,0),
 (263,'iPhone 6 16GB',4,1,0),
 (264,'iPhone 6 64GB',4,1,0),
 (265,'500 2GB Symbian',5,1,0),
 (266,'5233 Symbian',5,1,0),
 (267,'C6-00 Symbian',5,1,0),
 (268,'E5-00 Symbian',5,1,0),
 (269,'Lumia 1020 32GB 4G',5,1,0),
 (270,'Lumia 1320 8GB 4G',5,1,0),
 (271,'Lumia 1520 32GB 4G',5,1,0),
 (272,'Lumia 520 8GB',5,1,0),
 (273,'Lumia 530 4GB',5,1,0),
 (274,'Lumia 530 Dual',5,1,0),
 (275,'Lumia 620 8GB',5,1,0),
 (276,'Lumia 625 8GB 4G',5,1,0),
 (277,'Lumia 630 8GB',5,1,0),
 (278,'Lumia 630 8GB',5,1,0),
 (279,'Lumia 635 8GB 4G',5,1,0),
 (280,'Lumia 710 8GB',5,1,0),
 (281,'Lumia 720 8GB',5,1,0),
 (282,'Lumia 730 8GB',5,1,0),
 (283,'Lumia 735 8GB 4G',5,1,0),
 (284,'Lumia 820 8GB 4G',5,1,0),
 (285,'Lumia 830 16GB 4G',5,1,0),
 (286,'Lumia 830 16GB 4G',5,1,0),
 (287,'Lumia 925 16GB 4G',5,1,0),
 (288,'Lumia 930 32GB 4G',5,1,0),
 (289,'X Dual 4GB',5,1,0),
 (290,'Lumia 640 XL 8GB',6,1,0),
 (291,'Lumia 535 8GB',6,1,0),
 (292,'Lumia 435 Dual DTV 8GB',6,1,0),
 (293,'Lumia 640 DTV 8GB',6,1,0),
 (294,'Lumia 532 Dual DTV 8GB',6,1,0),
 (295,'Lumia 640 XL 8GB 4G',6,1,0),
 (296,'Lumia 532 8GB ',6,1,0),
 (297,'Lumia 435 8GB ',6,1,0),
 (298,'Lumia 950 32GB 4G',6,1,0),
 (299,'Lumia 950 XL 32GB',6,1,0),
 (300,'Xperia C Dual C2304 4GB',7,1,0),
 (301,'Xperia C4 E5303 16GB',7,1,0),
 (302,'Xperia C4 Selfie Dual E5333 16GB',7,1,0),
 (303,'Xperia C4 Selfie Dual E5343 16GB',7,1,0),
 (304,'Xperia C5 Ultra Dual 16GB',7,1,0),
 (305,'Xperia C5 Ultra Dual E5563 16GB',7,1,0),
 (306,'Xperia E Dual C1604 4GB',7,1,0),
 (307,'Xperia E1 D2004 4GB',7,1,0),
 (308,'Xperia E1 Dual D2114 4GB',7,1,0),
 (309,'Xperia E3 D2202 4GB',7,1,0),
 (310,'Xperia E3 D2206 4GB',7,1,0),
 (311,'Xperia E3 Dual D2212 4GB',7,1,0),
 (312,'Xperia E4 E2124 8GB',7,1,0),
 (313,'Xperia L C2104 8GB',7,1,0),
 (314,'Xperia M C1904 4GB',7,1,0),
 (315,'Xperia M Dual C2004 4GB',7,1,0),
 (316,'Xperia M2 Aqua D2403 8GB',7,1,0),
 (317,'Xperia M2 Aqua D2406 8GB',7,1,0),
 (318,'Xperia M2 D2303 8GB',7,1,0),
 (319,'Xperia M2 D2306 8GB',7,1,0),
 (320,'Xperia M4 Aqua E2306 16GB',7,1,0),
 (321,'Xperia M4 Aqua E2312 8GB',7,1,0),
 (322,'Xperia M4 Aqua E2333 16GB',7,1,0),
 (323,'Xperia M5 16GB 4G',7,1,0),
 (324,'Xperia S LT26i 32GB',7,1,0),
 (325,'Xperia SP C5303 8GB',7,1,0),
 (326,'Xperia T2 Ultra Dual D5322 8GB',7,1,0),
 (327,'Xperia T3 D5103 8GB',7,1,0),
 (328,'Xperia T3 D5106 8GB',7,1,0),
 (329,'Xperia U ST25i 4GB',7,1,0),
 (330,'Xperia Z Ultra C6833 16GB',7,1,0),
 (331,'Xperia Z1 C6906 16GB',7,1,0),
 (332,'Xperia Z1 C6943 16GB',7,1,0),
 (333,'Xperia Z2 D6543 16GB',7,1,0),
 (334,'Xperia Z3 Compact D5803 16GB',7,1,0),
 (335,'Xperia Z3 D6603 16GB',7,1,0),
 (336,'Xperia Z3 D6633 16GB',7,1,0),
 (337,'Xperia Z3 D6643 16GB',7,1,0),
 (338,'Xperia Z3+ E6533 32GB',7,1,0),
 (339,'Xperia Z5 32GB 4G',7,1,0),
 (340,'Xperia Z5 Compact 32GB 4G',7,1,0),
 (341,'Xperia Z5 Dual 32GB 4G',7,1,0),
 (342,'Xperia Z5 Premium 32GB 4G',7,1,0),
 (343,'Xperia ZQ C6503 16GB',7,1,0),
 (344,'Live G500TG 16GB',8,1,0),
 (345,'ZenFone 2 Deluxe Special Edition 256GB 4G',8,1,0),
 (346,'ZenFone 2 Deluxe ZE551ML 128GB',8,1,0),
 (347,'ZenFone 2 Laser ZE550KL 16GB',8,1,0),
 (348,'ZenFone 2 ZE550CL 16GB',8,1,0),
 (349,'ZenFone 2 ZE550ML 16GB',8,1,0),
 (350,'ZenFone 2 ZE551ML 16GB',8,1,0),
 (351,'ZenFone 2 ZE551ML 32GB',8,1,0),
 (352,'ZenFone 5 A501CG 1GB RAM 8GB',8,1,0),
 (353,'ZenFone 5 A501CG 2GB RAM 1.2GHz 8GB',8,1,0),
 (354,'ZenFone 5 A501CG 2GB RAM 1.6GHz 8GB',8,1,0),
 (355,'ZenFone 5 A501CG 2GB RAM 16GB',8,1,0),
 (356,'ZenFone 6 A601CG 16GB',8,1,0),
 (357,'ZenFone 6 A601CG 32GB',8,1,0),
 (358,'ZenFone Go ZC500TG 16GB',8,1,0),
 (359,'ZenFone Selfie ZD551KL 16GB',8,1,0),
 (360,'ZenFone Selfie ZD551KL 32GB',8,1,0),
 (361,'Idol 3 6039J 16GB 4G',9,1,0),
 (362,'Idol 6030D 16GB ',9,1,0),
 (363,'MPop 4GB ',9,1,0),
 (364,'One Touch Hero 2C 16GB 4G',9,1,0),
 (365,'One Touch Idol Mini 6012D 8GB',9,1,0),
 (366,'One Touch OT-890',9,1,0),
 (367,'One Touch Pixi 3 4028E 4GB',9,1,0),
 (368,'One Touch Pixi 4007D',9,1,0),
 (369,'One Touch Pop 2 5042A 8GB',9,1,0),
 (370,'One Touch Pop C1 4015N 4GB',9,1,0),
 (371,'One Touch Pop C1 4015X 4GB',9,1,0),
 (372,'One Touch Pop C3 4033A 4GB',9,1,0),
 (373,'One Touch Pop C3 4033E 4GB',9,1,0),
 (374,'One Touch Pop C5 5036D 4GB',9,1,0),
 (375,'One Touch Pop C5 5037E 4GB',9,1,0),
 (376,'One Touch Pop C7 7040D 4GB',9,1,0),
 (377,'One Touch Pop C7 OT7040E 4GB',9,1,0),
 (378,'One Touch Pop C9 7047A 4GB',9,1,0),
 (379,'One Touch Pop C9 7047D 4GB',9,1,0),
 (380,'Pixi 3 4009A 4GB',9,1,0),
 (381,'Pixi 3 4009E 4GB',9,1,0),
 (382,'Pixi 3 4013J 4GB',9,1,0),
 (383,'Pixi 3 4013K 4GB',9,1,0),
 (384,'Pop 3 5015A 8GB',9,1,0),
 (385,'Pop 3 5016J 8GB ',9,1,0),
 (386,'Pop C7 Plus OT7042E 4GB',9,1,0),
 (387,'Advance 4.0 L A010 4GB',10,1,0),
 (388,'Amour 4GB',10,1,0),
 (389,'Dash 4.0 D270 4GB',10,1,0),
 (390,'Dash Jr D140',10,1,0),
 (391,'Dash Jr. TV D141',10,1,0),
 (392,'Dash Music 2 D330 4GB',10,1,0),
 (393,'Dash Music Jr. D390',10,1,0),
 (394,'Life 8 L280 8GB',10,1,0),
 (395,'Life Pure L240i 32GB',10,1,0),
 (396,'Life Play 2 L170 8GB',10,1,0),
 (397,'Life Play L100i 4GB',10,1,0),
 (398,'Neo 4.5 S330L 4GB ',10,1,0),
 (399,'Studio 5.0 C D536 4GB',10,1,0),
 (400,'Studio 5.0 C HD D534 4GB',10,1,0),
 (401,'Studio 5.0 CE D536 4GB',10,1,0),
 (402,'Studio 5.0 D520 4GB',10,1,0),
 (403,'Studio 5.0 D530 4GB',10,1,0),
 (404,'Studio 6.0 HD D651 4GB',10,1,0),
 (405,'Studio C Mini D670 4GB',10,1,0),
 (406,'Studio Energy D810 8GB',10,1,0),
 (407,'Studio G D790 4GB',10,1,0),
 (408,'Vivo 4.3 D910i 4GB',10,1,0),
 (409,'Vivo Air D980L 16GB',10,1,0),
 (410,'Vivo IV D970L 16GB',10,1,0),
 (411,'Win HD W510 8GB',10,1,0),
 (412,'Win Jr. W410 4GB',10,1,0),
 (413,'Motion Colors SK412 4GB',11,1,0),
 (414,'Motion Plus SK351',11,1,0),
 (415,'Motion Plus SK352 4GB',11,1,0),
 (416,'Motion Plus SK402 ',11,1,0),
 (417,'Motion Plus SK504 ',11,1,0),
 (418,'Motion Plus TV SC452 4GB',11,1,0),
 (419,'Ascend G506 4GB',12,1,0),
 (420,'Ascend G510 4GB',12,1,0),
 (421,'Ascend P7 16GB 4G',12,1,0),
 (422,'Ascend Y210',12,1,0),
 (423,'Ascend Y320 4GB',12,1,0),
 (424,'Ascend Y330 4GB ',12,1,0),
 (425,'Google Nexus 6P 128GB 4G',12,1,0),
 (426,'Huawei Google Nexus 6P 32GB 4G',12,1,0),
 (427,' Huawei Google Nexus 6P 64GB 4G',12,1,0),
 (428,'Mercury P3169',13,1,0),
 (429,'MS1 P3242',13,1,0),
 (430,'MS2 P3278 4GB',13,1,0),
 (431,'MS4 P3248 4GB',13,1,0),
 (432,'MS5 Colors P3310 4GB',13,1,0),
 (433,'MS5 NB207 4GB',13,1,0),
 (434,'MS5 P3272 4GB',13,1,0),
 (435,'MS6 Colors NB211 8GB',13,1,0),
 (436,'MS6 Colors P3312 8GB',13,1,0),
 (437,'MS6 P3299 4GB',13,1,0),
 (438,'MSX P3304 4GB',13,1,0),
 (439,'Trend P3244',13,1,0),
 (440,'Órion P3181 ',13,1,0),
 (441,'Vibe A7010 32GB 4G',14,1,0),
 (442,'Octa X800 8GB',15,1,0),
 (443,'S 550 Kids 4GB',15,1,0),
 (444,'S380 4GB',15,1,0),
 (445,'S405 2 Chips',15,1,0),
 (446,'S440 4GB',15,1,0),
 (447,'S480 8GB',15,1,0),
 (448,'S550 4GB',15,1,0),
 (449,'X400 4GB',15,1,0),
 (450,'Ypy S350',15,1,0),
 (451,'Ypy S400 4GB',15,1,0),
 (452,'Ypy S450 4GB',15,1,0),
 (453,'Ypy S460 TV 4GB',15,1,0),
 (454,'Ypy S500 8GB',15,1,0),
 (455,'350 2 Chips',16,1,0),
 (456,'500 4GB',16,1,0),
 (457,'501 4GB',16,1,0),
 (458,'530 4GB',16,1,0),
 (459,'Mi 3 64GB',17,1,0),
 (460,'Mi 4 64GB 4G',17,1,0),
 (461,'Redmi 2 8GB 4G',17,1,0),
 (462,'Redmi 2 Pro 16GB',17,1,0),
 (463,'STILO XR3008',15,3,1);
/*!40000 ALTER TABLE `tb_modelo` ENABLE KEYS */;


--
-- Definition of table `tb_os`
--

DROP TABLE IF EXISTS `tb_os`;
CREATE TABLE `tb_os` (
  `cd_os` int(11) NOT NULL AUTO_INCREMENT,
  `dt_orcamento` date DEFAULT NULL,
  `dt_inicio` date DEFAULT NULL,
  `dt_finalizacao` date DEFAULT NULL,
  `dt_retirada` date DEFAULT NULL,
  `dt_expiracao` date DEFAULT NULL,
  `dt_venda` date DEFAULT NULL,
  `cd_status` int(1) DEFAULT NULL,
  `ic_orcam_vend_manuten` tinyint(1) DEFAULT NULL,
  `ds_os` varchar(500) DEFAULT NULL,
  `cd_funcionario` int(11) DEFAULT NULL,
  `dt_cadastro` datetime DEFAULT NULL,
  `cd_verificador` int(11) DEFAULT NULL,
  `cd_cliente` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_os`),
  UNIQUE KEY `uk_verificador` (`cd_verificador`) USING BTREE,
  KEY `fk_os_funcionario` (`cd_funcionario`),
  KEY `fk_os_status` (`cd_status`),
  KEY `fk_os_cliente` (`cd_cliente`),
  CONSTRAINT `fk_os_cliente` FOREIGN KEY (`cd_cliente`) REFERENCES `tb_cliente` (`cd_cliente`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_os_funcionario` FOREIGN KEY (`cd_funcionario`) REFERENCES `tb_funcionario` (`cd_funcionario`),
  CONSTRAINT `fk_os_status` FOREIGN KEY (`cd_status`) REFERENCES `tb_status` (`cd_status`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_os`
--

/*!40000 ALTER TABLE `tb_os` DISABLE KEYS */;
INSERT INTO `tb_os` (`cd_os`,`dt_orcamento`,`dt_inicio`,`dt_finalizacao`,`dt_retirada`,`dt_expiracao`,`dt_venda`,`cd_status`,`ic_orcam_vend_manuten`,`ds_os`,`cd_funcionario`,`dt_cadastro`,`cd_verificador`,`cd_cliente`) VALUES 
 (2,'2015-12-23','2015-12-23','2015-12-23',NULL,'2016-03-21',NULL,7,NULL,'Parte do pagamento será adiantado em dinheiro no valor de R$ 10,00',NULL,'2015-12-22 23:31:15',52721676,5),
 (3,'2015-12-27',NULL,NULL,NULL,NULL,NULL,1,NULL,'Teste',NULL,'2015-12-27 02:54:46',335660098,1),
 (4,'2015-12-27','2016-01-17','2016-03-23',NULL,'2016-06-21',NULL,7,NULL,'Mais um teste',NULL,'2015-12-27 03:02:26',199978770,5),
 (7,'2015-12-22','2016-01-18','2016-02-02',NULL,'2016-05-02',NULL,7,NULL,'Chatoooooooo',9,'2016-01-18 10:48:00',961975794,1),
 (8,'2016-01-18','2016-03-23',NULL,NULL,NULL,NULL,1,NULL,'Teste',2,'2016-01-19 00:25:20',863470482,5),
 (9,'2016-01-29',NULL,NULL,NULL,NULL,NULL,1,NULL,'Pagamento parcial, em dinheiro.',2,'2016-01-29 17:09:42',241829614,1),
 (10,'2016-01-29',NULL,NULL,NULL,NULL,NULL,3,NULL,'Teste',6,'2016-01-29 17:56:13',1172676987,1),
 (11,'2015-12-27',NULL,NULL,NULL,NULL,NULL,4,NULL,'kjandjkddad',3,'2016-01-29 18:03:27',619600269,1),
 (12,'2016-01-29',NULL,NULL,NULL,NULL,NULL,6,NULL,'iiqjiojqdqjdd',6,'2016-01-29 18:08:00',36953406,1),
 (13,'2016-01-29',NULL,NULL,NULL,NULL,NULL,6,NULL,'iiqjiojqdqjdd',6,'2016-01-29 18:09:08',762701959,1),
 (14,'2016-01-29',NULL,NULL,NULL,NULL,NULL,6,NULL,'iiqjiojqdqjdd',6,'2016-01-29 18:10:04',826405771,1),
 (15,'2016-01-29',NULL,NULL,NULL,NULL,NULL,6,NULL,'iiqjiojqdqjdd',6,'2016-01-29 18:10:27',836534566,1),
 (16,'2016-01-29',NULL,NULL,NULL,NULL,NULL,6,NULL,'iiqjiojqdqjdd',6,'2016-01-29 18:10:40',1161731953,1),
 (17,'2016-01-29',NULL,NULL,NULL,NULL,NULL,3,NULL,'wodjadjalda',2,'2016-01-29 18:13:26',817686846,1),
 (18,'2016-02-21',NULL,NULL,NULL,NULL,NULL,1,NULL,'',2,'2016-02-21 17:25:24',226469464,1);
/*!40000 ALTER TABLE `tb_os` ENABLE KEYS */;


--
-- Definition of table `tb_servico`
--

DROP TABLE IF EXISTS `tb_servico`;
CREATE TABLE `tb_servico` (
  `cd_servico` int(11) NOT NULL AUTO_INCREMENT,
  `ds_servico` varchar(100) DEFAULT NULL,
  `vl_custo` decimal(11,2) DEFAULT NULL,
  `vl_preco` decimal(11,2) DEFAULT NULL,
  `dt_solicitacao` datetime DEFAULT NULL,
  `cd_fornecedor` int(11) DEFAULT NULL,
  `cd_os` int(11) DEFAULT NULL,
  `cd_aparelho` int(11) DEFAULT NULL,
  PRIMARY KEY (`cd_servico`),
  KEY `fk_servico_fornecedor` (`cd_fornecedor`),
  KEY `fk_servico_os` (`cd_os`),
  KEY `fk_servico_aparelho` (`cd_aparelho`),
  CONSTRAINT `fk_servico_aparelho` FOREIGN KEY (`cd_aparelho`) REFERENCES `tb_aparelho` (`cd_aparelho`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_servico_fornecedor` FOREIGN KEY (`cd_fornecedor`) REFERENCES `tb_fornecedor` (`cd_fornecedor`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_servico_os` FOREIGN KEY (`cd_os`) REFERENCES `tb_os` (`cd_os`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_servico`
--

/*!40000 ALTER TABLE `tb_servico` DISABLE KEYS */;
INSERT INTO `tb_servico` (`cd_servico`,`ds_servico`,`vl_custo`,`vl_preco`,`dt_solicitacao`,`cd_fornecedor`,`cd_os`,`cd_aparelho`) VALUES 
 (1,'Serviço teste','100.00','300.00','2016-01-29 03:01:17',NULL,11,1),
 (2,'LED','50.00','100.00','2016-01-29 03:02:25',NULL,15,1),
 (3,'Tela','75.00','200.00','2016-01-29 03:02:25',NULL,17,1),
 (4,'Touch','50.00','175.00','2016-01-29 03:02:25',NULL,17,1),
 (10,'Memória RAM - 4GB','150.00','225.00','2016-02-21 17:07:00',NULL,18,42);
/*!40000 ALTER TABLE `tb_servico` ENABLE KEYS */;


--
-- Definition of table `tb_status`
--

DROP TABLE IF EXISTS `tb_status`;
CREATE TABLE `tb_status` (
  `cd_status` int(11) NOT NULL AUTO_INCREMENT,
  `nm_status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cd_status`),
  UNIQUE KEY `nm_satus` (`nm_status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_status`
--

/*!40000 ALTER TABLE `tb_status` DISABLE KEYS */;
INSERT INTO `tb_status` (`cd_status`,`nm_status`) VALUES 
 (4,'Aguardando peças'),
 (7,'Confirmado'),
 (6,'Em andamento'),
 (3,'Finalizado'),
 (1,'Orçamento'),
 (5,'Retirado');
/*!40000 ALTER TABLE `tb_status` ENABLE KEYS */;


--
-- Definition of table `tb_usuario`
--

DROP TABLE IF EXISTS `tb_usuario`;
CREATE TABLE `tb_usuario` (
  `cd_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nm_usuario` varchar(20) DEFAULT NULL,
  `cd_senha` varchar(50) DEFAULT NULL,
  `dt_cadastro` datetime DEFAULT NULL,
  `ic_logado` tinyint(1) DEFAULT NULL,
  `ic_bloqueado` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`cd_usuario`),
  UNIQUE KEY `nm_usuario` (`nm_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_usuario`
--

/*!40000 ALTER TABLE `tb_usuario` DISABLE KEYS */;
INSERT INTO `tb_usuario` (`cd_usuario`,`nm_usuario`,`cd_senha`,`dt_cadastro`,`ic_logado`,`ic_bloqueado`) VALUES 
 (1,'anytech','YW55dGVjaC5hbGw=',NULL,NULL,NULL);
/*!40000 ALTER TABLE `tb_usuario` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
