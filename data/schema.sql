--
-- Table structure for table `navigation_container`
--

DROP TABLE IF EXISTS `navigation_container`;
CREATE TABLE IF NOT EXISTS `navigation_container` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
  `active` tinyint(1) NOT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `target` varchar(15) DEFAULT NULL,
  `rel` tinytext,
  `rev` tinytext,
  `class` varchar(50) DEFAULT NULL,
  `properties` tinytext NOT NULL,
  `position` int(11) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `added_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=89 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `navigation_pages`
--
ALTER TABLE `navigation_pages`
  ADD CONSTRAINT `navigation_pages_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `navigation_container` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;