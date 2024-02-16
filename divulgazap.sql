-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Fev-2024 às 16:43
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
  `name` varchar(160) NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `visible` tinyint(4) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `groups`
--

INSERT INTO `groups` (`id`, `id_category`, `name`, `image`, `link`, `visible`, `description`, `created_at`, `updated_at`) VALUES
(1, 13, 'Figurinhas ', '65cf80af58c92.jpg', 'HgQf0TOFEUv63sdSKt2qAP', 1, '', '2024-02-16 15:35:11', '2024-02-16 15:35:11'),
(2, 18, 'Games e Jogos #01', '65cf80ddaa444.jpg', 'IZe4HOtnJ54CGAEDiTGvjL', 1, 'uma descrição legal aqui...', '2024-02-16 15:35:59', '2024-02-16 15:35:59'),
(3, 16, 'Cʜᴀᴛ ʙᴏᴛ Bᴇᴄᴄᴀ - Jeremias e Indião🌙', '65cf80fc322c7.jpg', 'DJnJZy5JSxfGIm0dLMYxTC', 1, '', '2024-02-16 15:36:28', '2024-02-16 15:36:28'),
(4, 13, '⫷Figurinhas 24 Horas⫸', '65cf811c143c0.jpg', 'HIUGQM65oBqAuKTncjqffa', 1, '', '2024-02-16 15:37:00', '2024-02-16 15:37:00');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
