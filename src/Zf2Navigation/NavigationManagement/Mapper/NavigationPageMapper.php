<?php
namespace nGen\Zf2Navigation\NavigationManagement\Mapper;

use nGen\Zf2Entity\Mapper\EntityStatisticsDbMapper;

class NavigationPageMapper extends EntityStatisticsDbMapper {	
    protected $tableName = "navigation_pages";
    protected $ordering = "ASC";
}   