# ToneHub — Planejamento de Sprints

O desenvolvimento está estruturado em **5 Sprints**, partindo da fundação do banco de dados e avançando progressivamente até o polimento visual e entrega final.

---

## Sprint 1 — Fundação: Banco de Dados, Models e Rotas

**Duração estimada:** ~3h  
**Módulos aplicados:** 09 (Migrations e Relacionamentos)

- [x] Configuração inicial do projeto (`.env`, timezone `America/Sao_Paulo`, locale `pt_BR`)
- [x] Instalar dependências Node (`npm install`) e verificar TailwindCSS no Vite
- [x] Criar Migration `create_items_table` com campos: title, description, category, hardware_model, cover_image_path, preset_file_path, dry_sample_path, wet_sample_path, downloads_count
- [x] Criar Migration `create_tags_table` com campo name (unique)
- [x] Criar Migration `create_item_tag_table` (tabela pivot N:M)
- [x] Criar Model `Item` com `$fillable`, relações `belongsTo(User)` e `belongsToMany(Tag)`, método `scopeSearch()`
- [x] Criar Model `Tag` com `$fillable` e relação `belongsToMany(Item)`
- [x] Adicionar relação `hasMany(Item)` no Model `User`
- [x] Definir rotas públicas: home (`/`), show item (`/items/{item}`), download (`/items/{item}/download`)
- [x] Definir rotas protegidas (`middleware auth`): CRUD de items, dashboard
- [x] Criar `HomeController` com listagem paginada + suporte a busca
- [x] Criar `ItemController` com métodos CRUD + download
- [x] Criar `DashboardController` com listagem de items do usuário logado
- [x] Executar `php artisan migrate` e validar estrutura do banco

---

## Sprint 2 — Autenticação e Autorização

**Duração estimada:** ~2h  
**Módulos aplicados:** 08 (Autenticação), 11 (Policies)

- [x] Criar `AuthController` com métodos: `showLogin`, `login`, `showRegister`, `register`, `logout`
- [x] Implementar registro com `Hash::make()` e `Auth::login()` automático
- [x] Implementar login com `Auth::attempt()` e suporte a `remember`
- [x] Implementar logout com `Auth::logout()` e invalidação de sessão
- [x] Definir rotas de autenticação: `GET/POST /login`, `GET/POST /register`, `POST /logout`
- [x] Criar `ItemPolicy` com métodos `update()` e `delete()` (verificação `$user->id === $item->user_id`)
- [x] Registrar a Policy (auto-discovery ou `AuthServiceProvider`)
- [x] Aplicar `$this->authorize()` nos controllers `edit`, `update`, `destroy`
- [ ] Usar diretivas `@auth`, `@guest`, `@can` nas views para exibição condicional de botões

---

## Sprint 3 — Upload de Arquivos e Validação

**Duração estimada:** ~3h  
**Módulos aplicados:** 12 (Upload de Arquivos), 07 (Validação de Requisições)

- [x] Executar `php artisan storage:link` para criar symlink público
- [x] Criar `StoreItemRequest` com regras de validação:
  - `title`: required, string, max:100
  - `description`: required, string, max:5000
  - `category`: required, in:IR,Capture,Preset
  - `hardware_model`: required, string, max:150
  - `cover_image`: nullable, image, mimes:jpg,jpeg,png,webp, max:2048
  - `preset_file`: required, file, max:20480 (20MB)
  - `dry_sample`: nullable, file, mimes:mp3,ogg,wav, max:10240 (10MB)
  - `wet_sample`: nullable, file, mimes:mp3,ogg,wav, max:10240 (10MB)
  - `tags`: nullable, string, max:500
- [x] Criar `UpdateItemRequest` (mesmas regras, `preset_file` nullable)
- [x] Implementar upload no `ItemController::store()` usando `Storage::disk('public')`
  - Subpastas organizadas: `covers/`, `presets/`, `samples/`
- [x] Implementar lógica de download com incremento de `downloads_count`
- [x] Implementar exclusão de arquivos antigos no `update()` e `destroy()`
- [x] Implementar lógica de sincronização de tags (parse de string separada por vírgula)
- [ ] Exibir erros de validação campo a campo com `@error` e preservar dados com `old()`

---

## Sprint 4 — Busca, Testes e Seeders

**Duração estimada:** ~2h  
**Módulos aplicados:** 10 (Integridade e Integração — Factories, Seeders, Testes)

- [x] Implementar `scopeSearch()` no Model `Item`:
  - Busca por `title`, `hardware_model`, `description` e `tags.name`
  - Usar `orWhereHas('tags', ...)` para busca em relações N:M
- [x] Integrar busca no `HomeController` com `$request->input('search')`
- [x] Criar `ItemFactory` com dados fake realistas (nomes de amps, categorias aleatórias)
- [x] Criar `TagFactory`
- [x] Configurar `DatabaseSeeder`:
  - 10 tags fixas (high-gain, clean, crunch, metal, blues, jazz, bass, acoustic, ambient, lead)
  - 5 usuários fake com 10 items cada = **50 items** no total
  - Cada item com 1-4 tags aleatórias
- [x] Criar `tests/Feature/ItemSearchTest.php`:
  - `test_search_finds_items_by_title`
  - `test_search_returns_empty_for_no_match`
  - `test_search_finds_items_by_tag`
- [x] Criar `tests/Feature/ItemPolicyTest.php`:
  - `test_owner_can_update_item`
  - `test_owner_can_delete_item`
  - `test_non_owner_cannot_update_item`
  - `test_non_owner_cannot_delete_item`
- [ ] Executar `php artisan test` e validar todos os testes passando

---

## Sprint 5 — Views Blade, TailwindCSS e Polimento Final

**Duração estimada:** ~4h  
**Módulos aplicados:** 05 (Views com Blade), 06 (TailwindCSS)

### Layout e Componentes Blade

- [x] Criar layout base `resources/views/layouts/app.blade.php`:
  - Navbar com logo, barra de busca, links de autenticação (`@auth`/`@guest`)
  - Footer com créditos
  - Uso de `@yield('content')` e `@stack('scripts')`
- [x] Criar componente `<x-item-card>`: card do preset com capa, título, categoria, tags, downloads
- [x] Criar componente `<x-audio-player>`: toggle Dry/Wet com Alpine.js e `<audio>` nativo
- [x] Criar componente `<x-tag-badge>`: badge estilizado para tags
- [x] Criar componente `<x-flash-message>`: mensagens de sucesso/erro com animação

### Páginas

- [x] `home.blade.php`: Feed principal com grid de cards, barra de busca, paginação
- [x] `items/show.blade.php`: Detalhe do item com player, info completa, botão de download
- [x] `items/create.blade.php`: Formulário de upload com preview de imagem
- [x] `items/edit.blade.php`: Formulário de edição (reuso de partials)
- [x] `dashboard/index.blade.php`: Tabela de gerenciamento dos items do usuário
- [x] `auth/login.blade.php`: Formulário de login estilizado
- [x] `auth/register.blade.php`: Formulário de registro estilizado

### Estilização TailwindCSS (Dark Mode)

- [x] Tema escuro como padrão: fundo `gray-900`/`gray-800`
- [x] Acentos em `amber-500`/`orange-500` (identidade visual de guitarra)
- [x] Cards com `rounded-xl shadow-lg hover:shadow-amber-500/20 transition`
- [x] Botões com gradiente `from-amber-500 to-orange-600`
- [x] Responsividade completa: `grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4`
- [x] Formulários: inputs com `bg-gray-700 border-gray-600 focus:ring-amber-500`

### Finalização

- [x] Popular banco com `php artisan migrate:fresh --seed` (50 items para demo)
- [ ] Atualizar `README.md` com print da tela principal
- [ ] Criar `.env.example` limpo
- [x] Testar fluxo completo: registro → login → upload → busca → download → editar → deletar
- [x] Testar que Policy bloqueia edição/exclusão por não-donos
- [ ] Gravar vídeo de defesa (máx. 10 minutos)
