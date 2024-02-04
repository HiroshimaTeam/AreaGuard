<?php
/**
 * ██╗░░██╗██╗██████╗░░█████╗░████████╗███████╗░█████╗░███╗░░░███╗
 * ██║░░██║██║██╔══██╗██╔══██╗╚══██╔══╝██╔════╝██╔══██╗████╗░████║
 * ███████║██║██████╔╝██║░░██║░░░██║░░░█████╗░░███████║██╔████╔██║
 * ██╔══██║██║██╔══██╗██║░░██║░░░██║░░░██╔══╝░░██╔══██║██║╚██╔╝██║
 * ██║░░██║██║██║░░██║╚█████╔╝░░░██║░░░███████╗██║░░██║██║░╚═╝░██║
 * ╚═╝░░╚═╝╚═╝╚═╝░░╚═╝░╚════╝░░░░╚═╝░░░╚══════╝╚═╝░░╚═╝╚═╝░░░░░╚═╝
 * AreaGuard-HiroTeam By WillyDuGang
 *
 * GitHub: https://github.com/HiroshimaTeam/AreaGuard
 */

declare(strict_types=1);

namespace HiroTeam\AreaGuard\libs\forms;

use pocketmine\form\Form as IForm;
use pocketmine\player\Player;

abstract class Form implements IForm {

	protected array $data = [];

	/** @var callable */
	private $callable;

	public function __construct(?callable $callable) {
		$this->callable = $callable;
	}

	/**
	 * @see Player::sendForm()
	 *
	 * @deprecated
	 */
	public function sendToPlayer(Player $player) : void {
		$player->sendForm($this);
	}

	public function handleResponse(Player $player, $data) : void {
		$this->processData($data);
		$callable = $this->getCallable();
		if ($callable !== null) {
			$callable($player, $data);
		}
	}

	public function processData(&$data) : void {
	}

	public function getCallable() : ?callable {
		return $this->callable;
	}

	public function setCallable(?callable $callable) : void {
		$this->callable = $callable;
	}

	public function jsonSerialize() : array {
		return $this->data;
	}
}
