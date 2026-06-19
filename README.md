# 🎸 ToneHub

> Plataforma web para compartilhamento de IRs (Impulse Responses), Capturas e Presets de guitarra, desenvolvida com Laravel 13.

## Sobre a aplicação

O **ToneHub** é uma plataforma web inspirada no [ToneHunt](https://www.tonehunt.org/), voltada para guitarristas e produtores musicais que desejam compartilhar e descobrir timbres. A aplicação permite que usuários façam upload de arquivos de preset/IR acompanhados de samples de áudio (Dry e Wet) para demonstração, além de categorizar por tipo de equipamento e tags.

**Público-alvo:** Guitarristas, baixistas e produtores musicais que utilizam modeladores de amp (ToneX, Quad Cortex, Kemper, HX Stomp) e precisam de uma plataforma centralizada para trocar timbres.

### Funcionalidades

- **Feed de timbres:** Página inicial estilo feed com os últimos uploads, ordenados por data
- **Busca robusta:** Pesquisa por título, modelo do amplificador, descrição e tags
- **Upload completo:** Envio de arquivo de preset/IR, imagem de capa e samples de áudio (Dry/Wet)
- **Player de áudio:** Toggle entre sample Dry (guitarra limpa) e Wet (com timbre aplicado) diretamente no navegador
- **Download público:** Visitantes podem baixar arquivos sem necessidade de cadastro
- **Dashboard pessoal:** Área privada para gerenciar uploads, editar descrições e remover itens
- **Autorização por Policy:** Apenas o dono do item pode editá-lo ou excluí-lo

### Print da tela principal

- **Tela home**
<img width="1905" height="940" alt="home" src="https://github.com/user-attachments/assets/36e9352d-17ab-4f4a-b623-72f88f8b9f5c" />

- **Tela de Login e Registro**
<img width="1905" height="940" alt="Captura de tela de 2026-06-18 22-37-28" src="https://github.com/user-attachments/assets/b2b19056-3e12-448f-bb7a-d19c747970d5" />
<img width="1905" height="940" alt="Captura de tela de 2026-06-18 22-37-40" src="https://github.com/user-attachments/assets/a9163c3c-aba5-4296-ba43-4e78ecd8d65e" />

- **Tela Biblioteca de Tones**
<img width="1905" height="940" alt="Captura de tela de 2026-06-18 22-39-13" src="https://github.com/user-attachments/assets/91dd0da5-5cfe-4a0a-9f32-fdaf7274caa7" />

- **Tela de Perfil Pública e Privada**
<img width="1905" height="940" alt="Captura de tela de 2026-06-18 22-40-08" src="https://github.com/user-attachments/assets/8900ff89-de35-467e-84dd-794051821b24" />
<img width="1905" height="940" alt="Captura de tela de 2026-06-18 22-40-27" src="https://github.com/user-attachments/assets/7e4e17cb-1111-457c-bb13-b65b606b5241" />

- **Tela de Upload e Edição de Tone**
<img width="1905" height="940" alt="Captura de tela de 2026-06-18 22-41-00" src="https://github.com/user-attachments/assets/9d8da6de-ab36-4faa-9aee-408555fee7b9" />

- **Tela Resultado de Pesquisa**
<img width="1905" height="940" alt="Captura de tela de 2026-06-18 22-41-44" src="https://github.com/user-attachments/assets/ecb7da7a-661a-4537-95b3-d26d950a1525" />

- **Tela de Detalhes (Tones do usuário e de outros)**
<img width="1905" height="940" alt="Captura de tela de 2026-06-18 22-48-02" src="https://github.com/user-attachments/assets/ed893eb3-f121-4b69-84df-6a40b12cdbe4" />
<img width="1905" height="940" alt="Captura de tela de 2026-06-18 22-53-29" src="https://github.com/user-attachments/assets/2947dbaa-98a6-4769-97ca-528a0ba5f725" />



---

## Execução do projeto

Para executar este projeto localmente, siga estes passos:

### Pré-requisitos

- **PHP 8.2+** com extensões: `pdo_sqlite`, `mbstring`, `openssl`, `fileinfo`
- **Composer** (gerenciador de dependências PHP)
- **Node.js 18+** e **npm**

### Instalação

```bash
# 1. Clone o repositório
git clone https://github.com/SEU_USUARIO/tonehub.git
cd tonehub

# 2. Instale as dependências PHP
composer install

# 3. Instale as dependências Node
npm install

# 4. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 5. Crie o banco de dados e popule com dados de exemplo
php artisan migrate --seed

# 6. Crie o link simbólico para arquivos públicos
php artisan storage:link

# 7. Inicie o Worker de Filas (necessário para processar uploads de áudio em background)
php artisan queue:work &

# 8. Compile os assets (CSS/JS) e inicie o servidor
npm run dev &
php artisan serve
```

A aplicação estará disponível em: **http://localhost:8000**

### Usuários de teste (gerados pelo Seeder)

Após executar `php artisan migrate --seed`, o banco terá 5 usuários e 50 itens de exemplo. Você pode se registrar com uma nova conta ou verificar os dados gerados via `php artisan tinker`.

---

## Estrutura de rotas

| Método | Rota | Descrição | Acesso |
|--------|------|-----------|--------|
| GET | `/` | Feed principal com busca e paginação | Público |
| GET | `/items/{item}` | Detalhe do item com player de áudio | Público |
| GET | `/items/{item}/download` | Download do arquivo de preset/IR | Público |
| GET | `/login` | Formulário de login | Público |
| POST | `/login` | Processar login | Público |
| GET | `/register` | Formulário de registro | Público |
| POST | `/register` | Processar registro | Público |
| POST | `/logout` | Encerrar sessão | Autenticado |
| GET | `/dashboard` | Gerenciamento dos meus uploads | Autenticado |
| GET | `/items/create` | Formulário de upload | Autenticado |
| POST | `/items` | Processar upload | Autenticado |
| GET | `/items/{item}/edit` | Formulário de edição | Autenticado (dono) |
| PUT | `/items/{item}` | Processar edição | Autenticado (dono) |
| DELETE | `/items/{item}` | Excluir item | Autenticado (dono) |

---

## Modelagem do banco de dados

O banco utiliza **SQLite** (padrão do Laravel 13) com 4 tabelas principais além da tabela `users`:

| Tabela | Descrição | Relacionamentos |
|--------|-----------|-----------------|
| `users` | Usuários da plataforma (padrão Laravel) | hasMany → items |
| `items` | Timbres/presets enviados pelos usuários | belongsTo → user, belongsToMany → tags |
| `tags` | Tags para categorização (ex: high-gain, clean) | belongsToMany → items |
| `item_tag` | Tabela pivot para relação N:M entre items e tags | — |

---

## Módulos da disciplina aplicados

Este projeto aplica de forma prática os seguintes **7 módulos** do curso *Desenvolvimento de Aplicações Backend com Framework* (UTFPR — TSI35D):

### 📖 05 | Views com Blade
Construção de todo o frontend usando a template engine **Blade**:
- Layout base com `@extends` e `@yield` para reuso de estrutura
- **Componentes reutilizáveis** customizados: `<x-item-card>` (card na listagem), `<x-audio-player>` (player web audio), `<x-tag-badge>` (tags), e formulários (`<x-form.input>`, `<x-form.select>`, `<x-form.textarea>`).
- Uso de **condicionais** (`@if`, `@auth`, `@guest`), **loops** (`@foreach`, `@forelse`) e **interpolação** (`{{ }}`)
- Componentes com **parâmetros** (props) para passagem de dados dinâmicos

### 📖 06 | Estilização com TailwindCSS
Estilização completa da aplicação com **TailwindCSS v4**:
- Tema dark-mode personalizado com paleta de cores "Sonic Obsidian" (fundo escuro `#131313` + acentos em púrpura/violeta `#d0bcff`)
- Design responsivo com grid adaptativo (`grid-cols-1` até `xl:grid-cols-4`)
- Cards com `hover` effects, gradientes em botões e transições suaves
- Formulários estilizados com estados visuais de foco e erro em dark mode

### 📖 07 | Validação de Requisições
Validação robusta de dados de entrada com **Form Requests**:
- `StoreItemRequest` e `UpdateItemRequest` com regras dedicadas
- Validação de tipos de arquivo (`mimes:jpg,png,webp` para imagens, `mimes:mp3,ogg,wav` para áudio)
- Limite de tamanho (`max:2048` para imagens, `max:20480` para presets)
- Exibição de erros campo a campo com `@error` e preservação de dados com `old()`

### 📖 08 | Autenticação de Usuários
Sistema de autenticação **implementado manualmente** (sem Starter Kit):
- Registro de usuários com hash de senha (`Hash::make`)
- Login com `Auth::attempt()` e suporte a "lembrar-me"
- Proteção de rotas privadas com middleware `auth`
- Uso de `@auth`/`@guest` nas views para controle de exibição

### 📖 09 | Migrações e Relacionamentos
Modelagem de banco de dados com **Migrations** e **Eloquent ORM**:
- 3 migrations customizadas (items, tags, item_tag) com chaves estrangeiras e `cascadeOnDelete`
- Relação **One to Many**: `User hasMany Items`
- Relação **Many to Many**: `Item belongsToMany Tags` (via tabela pivot `item_tag`)
- Definição completa de `$fillable` e métodos de relacionamento nos Models

### 📖 10 | Integridade e Integração
Garantia de qualidade com **Factories**, **Seeders** e **Testes**:
- `ItemFactory` e `TagFactory` para geração de dados fake realistas
- `DatabaseSeeder` populando 50 items com tags aleatórias (para demo no vídeo)
- Método `scopeSearch()` no Model Item para busca integrada
- Testes de Feature: busca por título, busca por tag, busca sem resultados
- Testes de Policy: verificação de autorização para editar/deletar

### 📖 12 | Upload de Arquivos
Sistema completo de upload e download usando a **facade Storage**:
- Upload de imagem de capa, arquivo de preset/IR e samples de áudio (Dry/Wet)
- Organização em subpastas (`covers/`, `presets/`, `samples/`) no disco `public`
- Geração de link simbólico com `php artisan storage:link`
- Download com contador de downloads (`$item->increment('downloads_count')`)
- Exclusão automática de arquivos antigos ao atualizar ou remover item

---

## Tecnologias utilizadas

| Tecnologia | Versão | Uso |
|------------|--------|-----|
| PHP | 8.2+ | Linguagem backend |
| Laravel | 13.x | Framework web |
| SQLite | 3.x | Banco de dados |
| Blade | — | Template engine (views) |
| TailwindCSS | 4.x | Estilização |
| Vite | 6.x | Bundler de assets |
| Alpine.js | 3.x | Interatividade frontend (player de áudio) |
| PHPUnit | 12.x | Testes automatizados |

---

## Licença

Este projeto foi desenvolvido como trabalho prático para a disciplina **TSI35D — Desenvolvimento de Aplicações Backend com Framework** da **UTFPR** (Universidade Tecnológica Federal do Paraná).
