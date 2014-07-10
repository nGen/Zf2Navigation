-- --------------------------------------------------------

--
-- Table structure for table `navigation_container`
--

DROP TABLE IF EXISTS `navigation_container`;
CREATE TABLE IF NOT EXISTS `navigation_container` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `navigation_pages`
--

DROP TABLE IF EXISTS `navigation_pages`;
CREATE TABLE IF NOT EXISTS `navigation_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `label` tinytext NOT NULL,
  `title` tinytext,
  `uri` tinytext,
  `menu` tinyint(1) NOT NULL,
  `breadcrumbs` tinyint(1) NOT NULL DEFAULT '1',
  `sitemap` tinyint(1) NOT NULL DEFAULT '1',
  `route` varchar(50) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `controller` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `params` tinytext,
  `reset_params` tinyint(1) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `target` varchar(15) DEFAULT NULL,
  `rel` tinytext,
  `rev` tinytext,
  `class` varchar(50) DEFAULT NULL,
  `properties` tinytext NOT NULL,
  `position` int(11) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
