-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08-Nov-2023 às 12:37
-- Versão do servidor: 10.4.25-MariaDB
-- versão do PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `divulgazap`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Outro', 'other.svg', '2023-10-29 11:54:10', '2023-10-29 11:54:10'),
(2, 'Academia', 'gym.svg', '2023-10-29 11:54:39', '2023-10-29 11:54:39'),
(3, 'Amor e Romance', 'love.svg', '2023-10-29 11:54:49', '2023-10-29 11:54:49'),
(4, 'Carros e Motos', 'car.svg', '2023-10-29 11:54:59', '2023-10-29 11:54:59'),
(5, 'Cidades', 'city.svg', '2023-10-29 11:55:05', '2023-10-29 11:55:05'),
(6, 'Compra e Venda', 'ecommerce.svg', '2023-10-29 11:55:13', '2023-10-29 11:55:13'),
(8, 'Desenhos e Animes', 'cartoon.svg', '2023-10-29 11:55:39', '2023-10-29 11:55:39'),
(9, 'Educação', 'study.svg', '2023-10-29 11:55:48', '2023-10-29 11:55:48'),
(11, 'Esportes', 'sport.svg', '2023-10-29 11:56:04', '2023-10-29 11:56:04'),
(12, 'Eventos', 'events.svg', '2023-10-29 11:56:11', '2023-10-29 11:56:11'),
(13, 'Figurinhas e Stickers', 'sticker.svg', '2023-10-29 11:56:24', '2023-10-29 11:56:24'),
(14, 'Filmes e Séries', 'movie.svg', '2023-10-29 11:56:32', '2023-10-29 11:56:32'),
(15, 'Frases e Mensagens', 'quote.svg', '2023-10-29 11:56:41', '2023-10-29 11:56:41'),
(16, 'Amizade', 'friend.svg', '2023-10-29 11:58:24', '2023-10-29 11:58:24'),
(17, 'Tecnologia e Programação', 'code.svg', '2023-10-30 11:44:55', '2023-10-30 11:44:55'),
(18, 'Games', 'game.svg', '2023-10-31 20:49:14', '2023-10-31 20:49:14'),
(19, 'Memes', 'meme.svg', '2023-10-31 20:49:14', '2023-10-31 20:49:14'),
(20, 'Músicas', 'music.svg', '2023-10-31 20:49:46', '2023-10-31 20:49:46'),
(21, 'Notícias', 'news.svg', '2023-10-31 20:49:46', '2023-10-31 20:49:46'),
(22, 'Receitas', 'food.svg', '2023-10-31 20:50:01', '2023-10-31 20:50:01'),
(26, 'Vagas de Emprego', 'job.svg', '2023-10-31 20:51:23', '2023-10-31 20:51:23'),
(27, 'Viagem e Turismo', 'travel.svg', '2023-10-31 20:51:39', '2023-10-31 20:51:39');

-- --------------------------------------------------------

--
-- Estrutura da tabela `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `name` varchar(150) NOT NULL,
  `image` varchar(500) NOT NULL,
  `link` varchar(500) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `groups`
--

INSERT INTO `groups` (`id`, `id_category`, `visible`, `name`, `image`, `link`, `description`, `created_at`, `updated_at`) VALUES
(1, 13, 1, '⭐Resenha das figurinhas⭐', 'https%3A%2F%2Fpps.whatsapp.net%2Fv%2Ft61.24694-24%2F390131610_168029076381601_7249968841341355535_n.jpg%3Fccb%3D11-4%26oh%3D01_AdQJBjn-e68mrInTdyARCUx5TgYp4kbgHQif_6wFwmlw_Q%26oe%3D65587D4B%26_nc_sid%3De6ed6c%26_nc_cat%3D103', 'HqMkuDYlxjOJYtljEow6lv', 'uma descrição do grupo aqui!', '2023-11-08 10:41:20', '2023-11-08 10:41:20'),
(2, 17, 1, 'Programação WEB', 'https%3A%2F%2Fpps.whatsapp.net%2Fv%2Ft61.24694-24%2F227605994_437600371473876_4302122585451980698_n.jpg%3Fccb%3D11-4%26oh%3D01_AdSiOrgv47wlCXDBPHWHhZ6iZ1KqEhRqdz3qzAUCquA6mA%26oe%3D65587304%26_nc_sid%3De6ed6c%26_nc_cat%3D111', 'KdvpeSuPoihCZWzYf8JMrG', '', '2023-11-08 11:23:47', '2023-11-08 11:23:47');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `groups` ADD FULLTEXT KEY `search` (`name`,`description`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
