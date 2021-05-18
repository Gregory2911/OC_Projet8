# OC_Projet8

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/3b15315afabd493ab76678c4c204e1ee)](https://www.codacy.com/gh/Gregory2911/OC_Projet8/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Gregory2911/OC_Projet8&amp;utm_campaign=Badge_Grade)

<h2><a id="user-content-pré-requis-" class="anchor" aria-hidden="true" href="#pré-requis-"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M7.775 3.275a.75.75 0 001.06 1.06l1.25-1.25a2 2 0 112.83 2.83l-2.5 2.5a2 2 0 01-2.83 0 .75.75 0 00-1.06 1.06 3.5 3.5 0 004.95 0l2.5-2.5a3.5 3.5 0 00-4.95-4.95l-1.25 1.25zm-4.69 9.64a2 2 0 010-2.83l2.5-2.5a2 2 0 012.83 0 .75.75 0 001.06-1.06 3.5 3.5 0 00-4.95 0l-2.5 2.5a3.5 3.5 0 004.95 4.95l1.25-1.25a.75.75 0 00-1.06-1.06l-1.25 1.25a2 2 0 01-2.83 0z"></path></svg></a>Pré-requis avant installation</h2>
<ul>
<li>PHP 7.3, MySQL, Symfony 5.2, Apache, Composer.</li>
</ul>

<h2><a id="user-content-installation" class="anchor" aria-hidden="true" href="#installation"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M7.775 3.275a.75.75 0 001.06 1.06l1.25-1.25a2 2 0 112.83 2.83l-2.5 2.5a2 2 0 01-2.83 0 .75.75 0 00-1.06 1.06 3.5 3.5 0 004.95 0l2.5-2.5a3.5 3.5 0 00-4.95-4.95l-1.25 1.25zm-4.69 9.64a2 2 0 010-2.83l2.5-2.5a2 2 0 012.83 0 .75.75 0 001.06-1.06 3.5 3.5 0 00-4.95 0l-2.5 2.5a3.5 3.5 0 004.95 4.95l1.25-1.25a.75.75 0 00-1.06-1.06l-1.25 1.25a2 2 0 01-2.83 0z"></path></svg></a>Installation</h2>

<p><strong>Etape 1</strong> Cloner le repository :</p>
<pre><code>git clone https://github.com/Gregory2911/OC_Projet8.git</code></pre>

<p><strong>Etape 2</strong> Installer les dépendances:</p>
<p>Exécuter la commande suivante dans le dossier à la racine du projet</p>
<pre><code>composer install</code></pre>

<p><strong>Etape 3 :</strong> Modification des informations de connexion à la base de données</p>
<p>Modifier la connexion à la base de données dans le fichier .env comme l'exemple suivant en choisissant un nom pour cette base :</p>
<pre><code>DATABASE_URL=mysql://root:@127.0.0.1:3308/todo?serverVersion=5.7</code></pre>

<p><strong>Etape 4 :</strong> Création de la base de données.</p>
<p>Exécuter la commande suivante dans le dossier à la racine du projet</p>
<pre><code>php bin/console doctrine:database:create</code></pre>

<p><strong>Etape 5 :</strong> Création de la structure de la base de données</p> 
<p>Exécuter la commande suivante dans le dossier à la racine du projet</p>
<pre><code>php bin/console doctrine:migrations:migrate</code></pre>

<p><strong>Etape 6 :</strong> Création d'un jeu de données</p> 
<p>Vous pouvez remplir la base avec un jeu de données de taches et d'utilisateurs en exécutant la commande suivante :</p>
<pre><code>php bin/console doctrine:fixtures:load</code></pre>

<p><strong>Etape 7 :</strong> Connexion en tant que admin</p> 
<p>Pour accèder à l'espace de gestion des utilisateurs il vous faut vous connecter en tant qu'administrateur.</p>
<p><strong>login</strong> : admin
<p><strong>mot de passe</strong> : password</p>

<p><strong>Votre application est désormais fonctionnelle.<strong></p>

<h2><a id="user-content-pré-requis-" class="anchor" aria-hidden="true" href="#pré-requis-"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M7.775 3.275a.75.75 0 001.06 1.06l1.25-1.25a2 2 0 112.83 2.83l-2.5 2.5a2 2 0 01-2.83 0 .75.75 0 00-1.06 1.06 3.5 3.5 0 004.95 0l2.5-2.5a3.5 3.5 0 00-4.95-4.95l-1.25 1.25zm-4.69 9.64a2 2 0 010-2.83l2.5-2.5a2 2 0 012.83 0 .75.75 0 001.06-1.06 3.5 3.5 0 00-4.95 0l-2.5 2.5a3.5 3.5 0 004.95 4.95l1.25-1.25a.75.75 0 00-1.06-1.06l-1.25 1.25a2 2 0 01-2.83 0z"></path></svg></a>Tests</h2>

<p>Pour exécuter les tests il faut lancer la commande suivante</p>
<pre><code>php bin/phpunit</code></pre>
<p>Vous trouverez le rapport du code coverage des tests dans le dossier tests/test-coverage puis ouvrez index.html dans votre navigateur.</p>

<h2><a id="user-content-pré-requis-" class="anchor" aria-hidden="true" href="#pré-requis-"><svg class="octicon octicon-link" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M7.775 3.275a.75.75 0 001.06 1.06l1.25-1.25a2 2 0 112.83 2.83l-2.5 2.5a2 2 0 01-2.83 0 .75.75 0 00-1.06 1.06 3.5 3.5 0 004.95 0l2.5-2.5a3.5 3.5 0 00-4.95-4.95l-1.25 1.25zm-4.69 9.64a2 2 0 010-2.83l2.5-2.5a2 2 0 012.83 0 .75.75 0 001.06-1.06 3.5 3.5 0 00-4.95 0l-2.5 2.5a3.5 3.5 0 004.95 4.95l1.25-1.25a.75.75 0 00-1.06-1.06l-1.25 1.25a2 2 0 01-2.83 0z"></path></svg></a>Documentation</h2>

<p><a href="https://github.com/Gregory2911/OC_Projet8/blob/main/documentation/documentation_technique.pdf">Documentation technique</a></p>
<p><a href="https://github.com/Gregory2911/OC_Projet8/blob/main/documentation/audit_qualite.pdf">Audit de qualité</a></p>
