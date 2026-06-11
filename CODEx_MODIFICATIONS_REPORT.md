# FunShirt - Relatorio de Modificacoes do Codex

## Observacoes

- Este documento resume **as modificacoes feitas por mim ao longo desta conversa**.
- As referencias de linhas usam a **numeracao atual dos ficheiros**.
- Quando uma alteracao foi grande, eu descrevo por **blocos de linhas** em vez de listar cada linha isolada.
- Para ficheiros removidos, nao ha numeracao atual; nesses casos explico o motivo da remocao.

---

## Ficheiros adicionados

### 1. `app/Requests/AdminUserStoreRequest.php`
- Linhas `1-43`: ficheiro criado para validar a criacao de utilizadores no backend administrativo.
- Linhas `10-13`: autorizacao restrita a administradores.
- Linhas `15-29`: regras para nome, email, genero, papel (`C/F/A`), password, foto e dados opcionais de customer.
- Linhas `31-42`: mensagens de erro especificas para email duplicado, genero invalido, role invalida, NIF, foto e metodo de pagamento.

### 2. `app/Requests/AdminUserUpdateRequest.php`
- Linhas `1-44`: ficheiro criado para validar a edicao de utilizadores no backend administrativo.
- Linhas `10-13`: autorizacao restrita a administradores.
- Linhas `15-31`: regras para atualizacao com `unique()->ignore($userId)` no email.
- Linhas `33-43`: mensagens de erro especificas para update.

### 3. `resources/views/layouts/main-content.blade.php`
- Linhas `1-26`: componente Blade criado para servir como wrapper de paginas internas com `title`, `heading`, `subheading` e `slot`.

---

## Ficheiros modificados

### 4. `routes/auth.php`
- Linhas `8-13`: as rotas de `register` e `login` passaram a usar `AuthController`, substituindo o fluxo antigo do Volt para autenticacao principal.
- Linhas `15-19`: mantive as rotas de `forgot-password` e `reset-password` em Volt.
- Linhas `22-33`: mantive verificacao de email e confirmacao de password; adicionei/ajustei `logout` para `AuthController@logout`.

### 5. `routes/web.php`
- Linhas `3-5`: imports reorganizados para `UserManagementController`, `Route` e `Storage`.
- Linha `12`: a homepage foi alterada para `Route::view('/', 'home')`.
- Linhas `14-18`: adicionei a rota `user.photo` para servir fotos do disco `public`.
- Linhas `25-27`: defini `/profile` como rota autenticada comum.
- Linhas `29-46`: organizei `dashboard` e o backend admin dentro de `auth + verified`.
- Linha `41`: troquei o backend de utilizadores para `Route::resource('users', UserManagementController::class)`.
- Linhas `43-44`: adicionei a rota `admin.users.toggle-block`.

### 6. `app/Http/Controllers/AuthController.php`
- Linhas `5-12`: imports limpos e organizados.
- Linhas `16-19`: `showLogin()` corrigido para `view('pages.auth.login')`.
- Linhas `21-50`: `login()` passou a:
  - validar email/password;
  - respeitar `remember`;
  - bloquear sessao de utilizador com `blocked = true`;
  - reenviar email de verificacao no primeiro login quando necessario;
  - redirecionar para `dashboard`.
- Linhas `53-56`: `showRegister()` corrigido para `view('pages.auth.register')`.
- Linhas `58-86`: `register()` passou a:
  - usar `RegisterFormRequest`;
  - criar `users.user_type = 'C'`;
  - guardar `gender`;
  - criar registo correspondente em `customers`;
  - fazer login imediato;
  - redirecionar para `verification.notice`.
- Linhas `89-95`: `logout()` padronizado com `invalidate()` e `regenerateToken()`.

### 7. `app/Http/Controllers/UserManagementController.php`
- Linhas `17-20`: mantive `ensureAdmin()` como fecho central de acesso admin.
- Linhas `22-98`: `index()` foi expandido para suportar:
  - pesquisa por nome/email;
  - filtro por role, estado, genero e verificacao;
  - ordenacao;
  - quantidade por pagina com opcao `all`;
  - `with('customer')` para carregar dados associados.
- Linhas `100-107`: `create()` passou a devolver a view admin correta.
- Linhas `109-134`: `store()` passou a:
  - usar `AdminUserStoreRequest`;
  - criar user com `blocked = false`;
  - guardar foto no disco `public`;
  - sincronizar dados opcionais de `customer`;
  - redirecionar para `admin.users.show`.
- Linhas `136-152`: `show()` e `edit()` passaram a carregar `customer`.
- Linhas `154-180`: `update()` passou a:
  - usar `AdminUserUpdateRequest`;
  - atualizar role/genero/email/nome;
  - substituir foto antiga de forma segura;
  - sincronizar dados de `customer`;
  - redirecionar para `admin.users.show`.
- Linhas `182-202`: `destroy()` passou a:
  - impedir auto-remocao do proprio admin;
  - fazer `soft delete` do `customer` associado;
  - fazer `soft delete` do `user`;
  - redirecionar para o index.
- Linhas `204-220`: `toggleBlock()` criado/ajustado para bloquear ou desbloquear so no ecrã de edicao.
- Linhas `222-245`: `syncCustomerDetails()` criado/ajustado para:
  - preservar dados de customer mesmo quando o role muda;
  - criar/atualizar registo `customers` quando houver dados para guardar.

### 8. `app/Http/Controllers/ProfileController.php`
- Linhas `3-9`: imports limpos; `ProfileUpdateFormRequest` passou a vir de `App\Requests`.
- Linhas `13-18`: `edit()` carrega `Auth::user()->load('customer')` e devolve `customer.profile`.
- Linhas `20-55`: `update()` passou a:
  - usar transacao;
  - atualizar foto com remocao segura da imagem anterior;
  - atualizar `users`;
  - atualizar `customers` apenas para contas `customer`;
  - redirecionar com mensagem de sucesso.

### 9. `app/Http/Controllers/CartController.php`
- Linhas `5-8`: imports limpos; `CartItemFormRequest` passou a usar `App\Requests`.
- Linhas `12-32`: `index()` recalcula quantidade total, desconto e subtotais.
- Linhas `34-65`: `store()` passou a consolidar itens iguais na sessao e a redirecionar para o carrinho.
- Linhas `67-79`: `update()` normalizado para atualizar quantidade.
- Linhas `81-92`: `destroy()` normalizado para remover item.
- Linhas `94-99`: `clear()` normalizado para limpar o carrinho.

### 10. `app/Http/Controllers/CheckoutController.php`
- Linha `7`: `CheckoutFormRequest` passou a usar `App\Requests`.
- Linhas `11-21`: `index()` protege checkout com carrinho nao vazio e carrega `customer`.
- Linhas `23-69`: `store()` passou a:
  - validar checkout via request;
  - recalcular totais no servidor;
  - gerar `orders` e `order_items` com valores historicos;
  - limpar a sessao do carrinho no fim.

### 11. `app/Http/Controllers/PriceController.php`
- Linha `6`: `PriceFormRequest` passou a usar `App\Requests`.
- Linhas `10-15`: `edit()` normalizado.
- Linhas `17-29`: `update()` normalizado para criar `Price` se ainda nao existir e guardar os valores.

### 12. `app/Http/Controllers/TshirtImageController.php`
- Linhas `5-9`: imports limpos; `TshirtImageFormRequest` passou a usar `App\Requests`.
- Linhas `13-26`: `index()` separado entre imagens privadas do cliente e catalogo administrativo.
- Linhas `35-58`: `store()` ajustado para:
  - guardar uploads privados em `local`;
  - guardar uploads publicos em `public`;
  - criar registo `tshirt_images`.
- Linhas `60-79`: `destroy()` ajustado para verificar permissao, apagar ficheiro fisico e fazer `soft delete` logico.
- Linhas `81-98`: `streamPrivateImage()` ajustado para proteger acesso a imagens privadas.

### 13. `app/Http/Controllers/OrderController.php`
- Linha `17`: comentario/criterio de permissao simplificado.
- Linhas `12-20`: `index()` garante que customer so ve as proprias encomendas.
- O resto do ficheiro foi mantido, com limpeza ligeira de estilo.

### 14. `app/Http/Controllers/Controller.php`
- Linha `5`: removi o comentario vazio `//`.

### 15. `app/Models/User.php`
- Linhas `5-15`: imports todos limpos e ordenados.
- Linhas `17-18`: mantive `Fillable` e `Hidden` com os campos corretos.
- Linhas `24-31`: `casts()` limpo, mantendo `email_verified_at`, `password` e `blocked`.
- Linhas `33-52`: `initials()` reescrito para:
  - remover lixo de comentarios;
  - lidar com nomes com espacos;
  - devolver iniciais consistentes;
  - devolver `U` se o nome estiver vazio.
- Linhas `54-63`: `getPhotoFullUrlAttribute()` limpo, mantendo fallback para `anonymous.png`.
- Linhas `65-80`: `hasUploadedPhoto()` e `normalizedPhotoPath()` mantidos e organizados.
- Linhas `83-101`: relacao `customer()` e helpers `isCustomer() / isStaff() / isAdmin()` limpos.

### 16. `app/Models/Customer.php`
- Linhas `5-13`: imports limpos.
- Linhas `15-22`: estrutura do model formatada e padronizada.
- Linhas `24-37`: relacoes `user()`, `orders()` e `tshirtImages()` mantidas com formatacao uniforme.

### 17. `app/Requests/RegisterFormRequest.php`
- Linhas `15-24`: regras confirmadas/alinhadas com o registo real:
  - `name`, `email`, `password`, `gender`, `nif`, `address`.
- O ficheiro foi mantido como base do registo customizado.

### 18. `app/Requests/ProfileUpdateFormRequest.php`
- Linha `3`: namespace unificado para `App\Requests`.
- Linhas `15-34`: regras mantidas e organizadas; dados de `customer` so entram quando o utilizador atual e customer.

### 19. `app/Requests/CheckoutFormRequest.php`
- Linha `3`: namespace unificado para `App\Requests`.
- Linhas `9-12`: autorizacao limitada a customers.
- Linhas `14-35`: validacao de checkout mantida com regras por tipo de pagamento.

### 20. `app/Requests/PriceFormRequest.php`
- Linha `3`: namespace unificado para `App\Requests`.
- Linhas `9-22`: autorizacao admin e regras de preco mantidas.

### 21. `app/Requests/TshirtImageFormRequest.php`
- Linha `3`: namespace unificado para `App\Requests`.
- Linhas `14-24`: regras organizadas com diferenca entre `POST` e `PUT/PATCH`.

### 22. `app/Requests/CartItemFormRequest.php`
- Linha `3`: namespace unificado para `App\Requests`.
- Linhas `14-20`: regras do item de carrinho mantidas e formatadas.

### 23. `resources/views/pages/auth/register.blade.php`
- Linhas `8-120`: formulario de registo refeito para usar `POST /register`.
- Linhas `11-37`: campos `name` e `email`.
- Linhas `40-63`: campos `password` e `password_confirmation`.
- Linhas `65-86`: campos `gender` e `nif`.
- Linhas `88-96`: campo `address`.
- Linhas `98-106`: bloco de erros.
- Linhas `108-118`: CTA final com link para login.

### 24. `resources/views/pages/auth/login.blade.php`
- Linhas `8-80`: formulario de login refeito para usar `POST /login`.
- Linhas `11-24`: campo `email`.
- Linhas `26-36`: campo `password`.
- Linhas `38-56`: checkbox `remember` e link de `forgot password`.
- Linhas `58-66`: bloco de erros.
- Linhas `68-79`: botao de submissao e link para `register`.

### 25. `resources/views/home.blade.php`
- Linhas `10-30`: script criado para persistir tema `light/dark` em `localStorage`.
- Linhas `31-63`: variaveis CSS para tema claro e escuro.
- Linhas `71-189`: homepage guest reestruturada com:
  - navbar;
  - botao de tema;
  - botoes login/register;
  - hero principal;
  - blocos de funcionalidade planeada.
- Linhas `192-247`: homepage autenticada criada com resumo do workspace e grupos G1-G8.
- Linhas `249-269`: script para alternancia do tema na home.

### 26. `resources/views/profile.blade.php`
- Linhas `8-32`: pagina profile reorganizada em grelha com:
  - coluna principal para `livewire:profile.profile`;
  - coluna lateral para password e delete account.

### 27. `resources/views/livewire/layout/navigation.blade.php`
- Linhas `16-39`: barra de navegacao principal refeita para `Home`, `Dashboard`, `Profile` e placeholders visuais.
- Linhas `40-49`: botao de tema no desktop.
- Linhas `51-111`: dropdown de utilizador refeito com avatar, nome, `Profile`, `User Management` (admin), `Products` (staff) e `Orders` (customer).
- Linhas `54-75` e `152-173`: avatar passou a suportar foto enviada ou iniciais.
- Linhas `114-195`: menu mobile refeito com a mesma logica de role.
- Linhas `198-221`: script de sincronizacao do tema na navbar.

### 28. `resources/views/livewire/profile/profile.blade.php`
- Linhas `16-23`: propriedades Livewire adicionadas para `nif`, `address`, `default_payment_type`, `default_payment_ref`, `photo_file`.
- Linhas `28-38`: `mount()` passou a carregar dados de `user` e `customer`.
- Linhas `43-88`: `updateProfileInformation()` passou a:
  - validar nome/email/foto/dados customer;
  - atualizar `users`;
  - limpar `email_verified_at` se o email mudar;
  - guardar/remover foto;
  - sincronizar `customers`;
  - disparar evento `profile-updated`.
- Linhas `90-100`: `deletePhoto()` adicionado.
- Linhas `105-118`: `sendVerification()` adicionado/ajustado.
- Linhas `132-172`: bloco visual de avatar, upload e delete photo.
- Linhas `174-211`: bloco `Account Details`.
- Linhas `213-254`: bloco `Billing & Payment Preferences`, marcado como opcional.
- Linhas `256-262`: area de submit/save.

### 29. `resources/views/admin/users/index.blade.php`
- Linhas `1-19`: filtros base definidos no topo.
- Linhas `21-63`: cabecalho admin da pagina redesenhado.
- Linhas `65-136`: area de pesquisa refeita com:
  - search;
  - role;
  - status;
  - gender;
  - email verification;
  - reset/search.
- Linhas `149-260`: tabela principal refeita com:
  - avatar circular de `50px`;
  - role;
  - gender;
  - email verified;
  - blocked/active;
  - created date;
  - actions `Show/Edit/Delete`.
- Linhas `168-200`: `Sort` movido para o cabecalho da tabela, com submissao automatica.
- Linhas `263-296`: rodape com paginacao e seletor `Show 10/20/50/100/All`.

### 30. `resources/views/admin/users/create.blade.php`
- Linhas `6-41`: pagina create refeita com cabecalho admin, erro visual, formulario e botoes `Save User / Cancel`.

### 31. `resources/views/admin/users/show.blade.php`
- Linhas `6-29`: pagina show refeita como view somente leitura com botoes `Edit` e `Back to Index`.

### 32. `resources/views/admin/users/update.blade.php`
- Linhas `18-32`: mensagens de estado/erro organizadas.
- Linhas `34-58`: formulario de edicao com `Save Changes`.
- Linhas `45-56`: botoes `Delete User` e `Cancel`.
- Linhas `60-73`: formularios auxiliares separados para delete e toggle block.

### 33. `resources/views/admin/users/partials/fields.blade.php`
- Linhas `1-18`: definicao de modo (`create/edit/show`) e variaveis auxiliares.
- Linhas `20-109`: secao `Identity` criada/refeita.
- Linhas `112-178`: secao `Billing & Payment Details` criada/refeita.
- Linhas `181-220`: secao `Avatar` criada/refeita com preview maior no `show`.
- Linhas `222-265`: secao `Account Status` criada/refeita.
- Linhas `267-287`: secao `Administrative Actions` criada/refeita, com botao de block/unblock apenas no modo `edit`.

---

## Ficheiros removidos

### 34. `app/Http/Controllers/UserController.php`
- Ficheiro removido.
- Motivo:
  - nao tinha qualquer rota ligada;
  - nao era referenciado em views ou controllers;
  - continha apenas retornos placeholder como `"index profile"`;
  - o nome da classe ja estava incorreto (`class c`), o que confirmava tratar-se de residuo antigo.

---

## Resumo final

- Backend de autenticacao customizado: **ativo**.
- Backend administrativo de utilizadores: **reestruturado com CRUD, filtros, bloqueio e soft delete**.
- Requests: **namespace unificado em `App\Requests`**.
- Models: **comentarios e imports limpos; `User.php` reescrito de forma segura**.
- Homepage, login, register, profile e navbar: **reestruturados visualmente**.
- Ficheiro obsoleto `UserController.php`: **removido**.
