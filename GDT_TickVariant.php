<?php
namespace GDO\DogTick;

use GDO\Core\GDT_EnumNoI18n;

final class GDT_TickVariant extends GDT_EnumNoI18n
{

	protected function __construct()
	{
		parent::__construct();
		$this->notNull();
		$config = Module_DogTick::instance()->coronaConfig();
		$this->enumValues(...array_keys($config));
	}


}
