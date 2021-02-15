<?php

namespace derc\doit;

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\math\Vector3;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\Server;

class main extends PluginBase implements Listener{

    public $prefix;

    public function onEnable()
    {
        $this->prefix = "§l§cDO§r-§aIT §r» ";
        $this->getLogger()->notice("DOIT by DerCooleVonDem was enabled");
        $cfg = $this->getConfig();
        $cfg->save();
        $this->getLogger()->notice("DOIT Config was found and loaded");
        $this->getLogger()->info("Have Fun and keep save!");
    }
    public function onDisable()
    {
        $cfg = $this->getConfig();
        $cfg->save();
        $this->getLogger()->notice("DOIT was successfully disabled");
        $this->getLogger()->notice("All Changes saved!");
    }
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $msg = implode(" ", $args);
        $wordlist = explode(" ", $msg);
        $victim = $wordlist[0];
        $command1 = str_replace($victim, "", $msg);
        $count = 1;
        $command2 = str_replace(" ", "", $msg, $count);

        //---------------------------------------------------------------------------------------------------

        switch ($command->getName()){
            case "everyone":
                if($sender->hasPermission("doit.everyone") or $sender->hasPermission("doit.*")){
                    if(!$wordlist[0] == null) {
                        foreach ($this->getServer()->getOnlinePlayers() as $player) {
                            if(!$player->isOp()){
                                $this->getServer()->dispatchCommand($player, $command2);
                                $sender->sendMessage($this->prefix."§a[/".$command2."] was successfully executed for EVERYONE");
                                foreach ($this->getServer()->getOnlinePlayers() as $plyr){
                                    if(!$plyr === $sender){
                                        if($plyr->isOp()){
                                            $plyr->sendMessage("§o[".strtoupper($sender->getName()).": [/$command2] for everyone]");
                                        }
                                    }


                                }
                            }else{

                                $player->sendMessage($this->prefix."§aYou have been protected from an /everyone by ".$sender->getName());

                            }

                        }
                        $this->playSound(Server::getInstance()->getPlayer($sender->getName()), 62);
                    }else{

                        $sender->sendMessage($this->prefix . "§cUsage: /everyone [command]");


                    }
                }else{

                    $sender->sendMessage($this->prefix."§cSorry, but you dont have the Permission to use /everyone!");

                }
                break;



            case "run":
                if($sender->hasPermission("doit.run") or $sender->hasPermission("doit.*")){
                    if(!$wordlist[0] == null){
                        if(!$wordlist[1] == null){
                            if(!$this->getServer()->getPlayer($victim) == null){
                                if(!$this->getServer()->getPlayer($victim)->isOp()){
                                    $ply = $this->getServer()->getPlayer($victim);
                                    $this->getServer()->dispatchCommand($ply, $command1);
                                    $sender->sendMessage($this->prefix."§a[/$command1] was successfully executed for ".$ply->getName());
                                    foreach ($this->getServer()->getOnlinePlayers() as $plyr){
                                        if(!$plyr === $sender){
                                            if($plyr->isOp()){
                                                $plyr->sendMessage("§o[".strtoupper($sender->getName()).": [/".trim(implode(" ", $args))."] for ".$this->getServer()->getPlayer($victim)->getName()."]");
                                            }
                                        }


                                    }
                                }else{
                                    $sender->sendMessage($this->prefix."§cYou cant run this command on an player which is an Operator!");

                                }


                            }else{

                                $sender->sendMessage($this->prefix."§cThis Player was not found! Try later...");

                            }

                        }else{

                            $sender->sendMessage($this->prefix . "§cUsage: /run [victim] [command]");


                        }

                    }else{

                        $sender->sendMessage($this->prefix . "§cUsage: /run [victim] [command]");


                    }


                }else{

                    $sender->sendMessage($this->prefix."§cSorry, but you dont have the Permission to use /run!");

                }
                break;
            case "console":
                if($sender->hasPermission("doit.console") or $sender->hasPermission("doit.*")){
                    if(!$wordlist[0] == null){
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), trim(implode(" ", $args))); //TODO Do this for every
                        $sender->sendMessage($this->prefix."§a[/".trim(implode(" ", $args))."] was successfully executed for the Console");
                        foreach ($this->getServer()->getOnlinePlayers() as $plyr){
                            if(!$plyr === $sender){
                                if($plyr->isOp()){
                                    $plyr->sendMessage("§o[".strtoupper($sender->getName()).": [/".trim(implode(" ", $args))."] for console]");
                                }
                            }


                        }

                    }else{

                        $sender->sendMessage($this->prefix . "§cUsage: /console [command]");


                    }


                }else{

                    $sender->sendMessage($this->prefix."§cSorry, but you dont have the Permission to use /console!");

                }
                break;
            case "others":
                if($sender->hasPermission("doit.others") or $sender->hasPermission("doit.*")){
                    if(!$wordlist[0] == null) {
                        foreach ($this->getServer()->getOnlinePlayers() as $player) {
                            if(!$player === $sender) {
                                if (!$player->isOp()) {

                                    $this->getServer()->dispatchCommand($player, $command2);
                                    $sender->sendMessage($this->prefix . "§a[/$command2] was successfully executed for everyone but you");
                                    foreach ($this->getServer()->getOnlinePlayers() as $plyr){
                                        if(!$plyr === $sender){
                                            if($plyr->isOp()){
                                                $plyr->sendMessage("§o[".strtoupper($sender->getName()).": [/$command2] for others]");
                                            }
                                        }


                                    }

                                } else {

                                    $player->sendMessage($this->prefix . "§aYou have been protected from an /others by " . $sender->getName());

                                }
                            }
                        }
                        $this->playSound(Server::getInstance()->getPlayer($sender->getName()), 62);
                    }else{

                        $sender->sendMessage($this->prefix . "§cUsage: /others [command]");


                    }
                }else{

                    $sender->sendMessage($this->prefix."§cSorry, but you dont have the Permission to use /others!");

                }
                break;


        }
        return true;
    }
    public function playSound(Player $player, int $id)
    {
        $pk = new LevelSoundEventPacket();
        $pk->sound = $id;
        $pk->position = new Vector3($player->x, $player->y, $player->z);
        $player->dataPacket($pk);
    }
}