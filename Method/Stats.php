<?php
declare(strict_types=1);
namespace GDO\DogTick\Method;

use GDO\Core\GDT;
use GDO\Core\GDT_Enum;
use GDO\Core\GDT_Response;
use GDO\Core\GDT_UInt;
use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Dog\DOG_User;
use GDO\DogTick\DOG_Tick;
use GDO\Table\GDT_PageMenu;

/**
 * Show game statistics.
 *
 * @author gizmore
 */
final class Stats extends DOG_Command
{

	public int $priority = 60;

	public int $ipp = 10;

	public function getCLITrigger(): string
	{
		return 'cc.stats';
	}

	public function gdoParameters(): array
	{
		return [
			GDT_Enum::make('section')->enumValues('total', 'top10', 'victims')->notNull()->initial('total'),
			GDT_UInt::make('page')->min(1)->initial('1'),
		];
	}

	public function dogExecute(DOG_Message $message, string $section, int $page): GDT
	{
		if ($section === 'total')
		{
			$bestPlayer = DOG_Tick::bestPlayer();
			if (!$bestPlayer)
			{
				return $message->rply('err_no_data');
			}
			return $message->rply('msg_dog_tickstats_total',
				[
					DOG_Tick::totalVictims(),
					$bestPlayer->displayFullName(),
					DOG_Tick::numTicks($bestPlayer),
				]);
		}
		elseif ($section === 'top10')
		{
			$nItems = DOG_Tick::table()->select('COUNT(DISTINCT(tick_by))')
				->exec()
				->fetchVar();
			if ($nItems == 0)
			{
				return $message->rply('err_no_data');
			}
			$nPages = GDT_PageMenu::getPageCountS((int)$nItems, $this->ipp);
			if ($page > $nPages)
			{
				return $message->rply('err_page', [
					$nPages,
				]);
			}

			$start = GDT_PageMenu::getFromS($page, $this->ipp);
			$query = DOG_Tick::table()->select('tick_by, COUNT(*) count')
				->group('tick_by')
				->order('count DESC')
				->order('tick_at')
				->limit($this->ipp, $start);
			$data = $query->exec()->fetchAllRows();
			$back = '';
			foreach ($data as $row)
			{
				$user = DOG_User::findById($row[0]);
				$rank = ++$start;
				$back .= ", {$rank}-{$user->displayFullName()}({$row[1]})";
			}
			$back = trim($back, ', ');

			return $message->rply('msg_dog_tickstats_top10', [
				$nItems,
				$page,
				$nPages,
				$back,
			]);
		}
		elseif ($section === 'victims')
		{
			$nItems = DOG_Tick::table()->select('COUNT(DISTINCT(tick_to))')
				->exec()
				->fetchVar();
			if ($nItems == 0)
			{
				return $message->rply('err_no_data');
			}
			$nPages = GDT_PageMenu::getPageCountS((int)$nItems, $this->ipp);
			if ($page > $nPages)
			{
				return $message->rply('err_page', [
					$nPages,
				]);
			}

			$start = GDT_PageMenu::getFromS($page, $this->ipp);
			$query = DOG_Tick::table()->select('tick_to, COUNT(*) count')
				->group('tick_to')
				->order('tick_at DESC')
				->limit($this->ipp, $start);
			$data = $query->exec()->fetchAllRows();
			$back = '';
			foreach ($data as $row)
			{
				$user = DOG_User::findById($row[0]);
				$rank = ++$start;
				$back .= ", {$rank}-{$user->displayFullName()}";
			}
			$back = trim($back, ', ');

			return $message->rply('msg_dog_tickstats_victims', [
				$nItems,
				$page,
				$nPages,
				$back,
			]);
		}

		return GDT_Response::make();
	}

}
