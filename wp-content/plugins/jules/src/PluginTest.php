<?php

namespace Jules\Plugin;

use Exception;

class PluginTest
{
    const TRANSIENT_PLUGINTEST_ACTIVATED = "jules_plugintest_activated";
    const TRANSIENT_PLUGINTEST_DEACTIVATED = "jules_plugintest_deactivated";
    var string $m_file = "";

    /**
     * Constructeur de PluginTest
     * 
     * @param string $file Habituellement __FILE__ 
     *  
     * @return void
     */
    public function __construct(string $file)
    {
        $this->m_file = $file;  // Assign the file to the instance variable

        register_activation_hook($this->m_file, array($this, 'plugin_activation'), 10); // 10 permet de modifier la priorité
        add_action('admin_notices', array($this, 'notice_activation'));
        
        register_deactivation_hook($this->m_file, array($this, 'plugin_deactivation'), 10);
        add_action('admin_notices', array($this, 'notice_deactivation'));
        }
    
    /**
	 * Suivi de l'activation du plugin
	 *
	 * @return void
	 */
    public function plugin_activation() {
        set_transient(self::TRANSIENT_PLUGINTEST_ACTIVATED, true);     
    }

    /**
	 * Suivi de la désactivation du plugin
	 *
	 * @return void
	 */
    public function plugin_deactivation() {
        set_transient(self::TRANSIENT_PLUGINTEST_DEACTIVATED, true);
    }

    /**
	 * Envoie d'un message si l'activation du plugin est un succès
	 *
	 * @return void
	 */
	public function notice_activation() : void
	{
		try {
			$transient_value = get_transient(self::TRANSIENT_PLUGINTEST_ACTIVATED);
			if ($transient_value) {
				self::render('notices', [
					'message' => "Merci d'avoir activé <strong>PluginTest</strong> !"
				]);
				delete_transient(self::TRANSIENT_PLUGINTEST_ACTIVATED); 
			} else {
				throw new Exception("PluginTest : erreur lors de l'activation");
			}
		} catch (Exception $e) {
			error_log('Erreur dans notice_activation: ' . $e->getMessage());
		}
	}
    
	/**
	 * Envoie d'un message si la désactivation du plugin est un succès
	 *
	 * @return void
	 */
	public function notice_deactivation() : void
	{
		try {
			$transient_value = get_transient(self::TRANSIENT_PLUGINTEST_DEACTIVATED);
			if ($transient_value) {
				self::render('notices', [
					'message' => "<strong>PluginTest</strong> a été désactivé !"
				]);
				delete_transient(self::TRANSIENT_PLUGINTEST_DEACTIVATED); 
			} else {
				throw new Exception("PluginTest : erreur lors de la désactivation");
			}
		} catch (Exception $e) {
			error_log('Erreur dans notice_deactivation: ' . $e->getMessage());
		}
	}

    /**
	 * Inclure et afficher des vues
	 *
	 * @param string $name Nom du fichier
	 * @param array $args Message que vous voulez afficher
	 *
	 * @return void
	 */
	public static function render(string $name, array $args = []) : void
	{
		extract($args);

		$file = JULES_PLUGIN_DIR . "views/$name.php";

		ob_start(); // Démarrage de la mémoire tampon

		include_once ($file);

		echo ob_get_clean(); // Clear de la mémoire tampon
	}
}
