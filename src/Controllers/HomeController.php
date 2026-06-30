<?php

declare(strict_types=1);

/*
===============================================================================
FNLLA PHP CONTROLLER SOURCE
File: src\Controllers\HomeController.php
Copyright (c) 2026 TechAyo LTD (techayo.co.uk). Released under the MIT License.
===============================================================================

FNLLA PHP is produced, maintained and distributed by TechAyo LTD
(techayo.co.uk). This repository is the authoritative maintainer workspace for
the FNLLA PHP framework released under the MIT License and its related delivery scripts, tests,
templates and release metadata.

Purpose:
- Provides HTTP-facing controller behavior for maintained framework flows and demos.
*/

namespace Fnlla\Php\Controllers;

use Fnlla\Php\Http\Request;
use Fnlla\Php\Http\Response;
use Fnlla\Php\Validation\ValidationException;

final class HomeController extends Controller
{
    public function home(Request $request): Response
    {
        return $this->view("pages/home", [
            "pageTitle" => "Overview",
            "pageTitleHome" => true,
            "proofCards" => [
                [
                    "title" => "Readable request flow",
                    "text" => "Front controller, bootstrap, router, controller and view stay visible instead of disappearing behind layers of framework magic.",
                ],
                [
                    "title" => "UI runtime already solved",
                    "text" => "FNLLA Web provides the shared page, component and interaction language, so the PHP starter can stay focused on delivery logic.",
                ],
                [
                    "title" => "Release hygiene stays explicit",
                    "text" => "Validation, version metadata and runtime synchronization commands remain close to the repository instead of being hidden in a package registry workflow.",
                ],
            ],
            "platformStats" => [
                [
                    "value" => "1",
                    "label" => "official UI runtime boundary: `public/vendor/fnlla-web/`",
                ],
                [
                    "value" => "0",
                    "label" => "required external CDNs or JS framework dependencies in the starter surface",
                ],
                [
                    "value" => "5",
                    "label" => "primary request stages a new team can trace in one sitting",
                ],
                [
                    "value" => "6",
                    "label" => "project-facing validation and sync routines kept directly in the starter",
                ],
            ],
            "workflowSteps" => [
                [
                    "number" => "1",
                    "title" => "Export",
                    "text" => "Generate a new downstream project from `fnlla/php` instead of building client work directly inside the framework repository.",
                ],
                [
                    "number" => "2",
                    "title" => "Shape",
                    "text" => "Replace the demo routes, controllers and templates with the real page map, forms and protected areas for the project.",
                ],
                [
                    "number" => "3",
                    "title" => "Harden",
                    "text" => "Run FNLLA Web validation, tests, lint and version checks so the project stays aligned with the supported contract.",
                ],
            ],
            "faqItems" => [
                [
                    "question" => "Why not just clone `fnlla/php` and start building the client project there?",
                    "answer" => "Because framework maintenance and one downstream delivery are different concerns. `make:project` keeps the framework repo clean and gives the new app its own lifecycle.",
                ],
                [
                    "question" => "Does the starter still stay small enough for one developer to understand quickly?",
                    "answer" => "Yes. The starter keeps the request lifecycle explicit on purpose, while FNLLA Web absorbs the repeated page, component and interaction patterns.",
                ],
                [
                    "question" => "Can the starter support more than marketing pages?",
                    "answer" => "Yes. Auth, authorization, validation, migrations, query builder, queues and scheduling are already present for internal tools, service portals and admin surfaces.",
                ],
                [
                    "question" => "What is the intended first proof step after export?",
                    "answer" => "Validate FNLLA Web, run the local tests and lint, then confirm the version contract before deeper delivery work begins.",
                ],
            ],
        ]);
    }

    public function platform(Request $request): Response
    {
        return $this->view("pages/platform", [
            "pageTitle" => "Platform",
            "pageTitleSection" => "Framework",
            "platformTabs" => [
                [
                    "label" => "Request flow",
                    "title" => "A lean HTTP pipeline that still covers real delivery needs",
                    "text" => "Requests come through `public/index.php`, move into bootstrap, route matching, middleware, controller logic and finally one shared layout. That keeps debugging and onboarding fast.",
                ],
                [
                    "label" => "UI runtime",
                    "title" => "FNLLA Web remains the only supported UI layer",
                    "text" => "Layouts, cards, overlays, accordions, responsive nav and section systems come from the vendored FNLLA Web runtime, so downstream projects avoid mixed design-system drift.",
                ],
                [
                    "label" => "Data and auth",
                    "title" => "Enough application foundation without a full-stack abstraction tax",
                    "text" => "The starter includes MySQL access, migrations, validation, sessions, auth, authorization and CLI operations without forcing a large ORM or a compiled front-end stack.",
                ],
                [
                    "label" => "Release hygiene",
                    "title" => "Version and runtime checks stay visible in the repository",
                    "text" => "FNLLA Web sync, version manifest validation and starter-local tests are explicit commands so release integrity can be proven instead of assumed.",
                ],
            ],
            "capabilityCards" => [
                [
                    "title" => "HTTP and middleware",
                    "text" => "Named routes, route groups, middleware aliases, throttling and explicit request/response handling stay readable in the repo.",
                ],
                [
                    "title" => "Auth and protected areas",
                    "text" => "Session-backed auth, route protection and authorization gates cover dashboards, internal tools and admin surfaces without custom glue code.",
                ],
                [
                    "title" => "Operational commands",
                    "text" => "Migration, queue, schedule, FNLLA Web sync and version commands ship with the starter so the project can evolve past brochure-site scope cleanly.",
                ],
            ],
            "platformStats" => [
                [
                    "value" => "PHP 8.3",
                    "label" => "official runtime baseline for the maintained framework",
                ],
                [
                    "value" => "MySQL",
                    "label" => "supported database contract in the official stack",
                ],
                [
                    "value" => "Local",
                    "label" => "vendored FNLLA Web assets, shipped inside the project itself",
                ],
                [
                    "value" => "GitHub",
                    "label" => "source-of-truth workflow for framework and runtime updates",
                ],
            ],
            "platformFaqs" => [
                [
                    "question" => "Is FNLLA PHP trying to compete by being larger than Laravel or Symfony?",
                    "answer" => "No. Its advantage is a smaller, more inspectable surface that still covers the practical application needs of teams shipping server-rendered work.",
                ],
                [
                    "question" => "Why keep FNLLA Web bundled locally instead of using a CDN?",
                    "answer" => "A local vendored runtime keeps deployments offline-safe, predictable and easier to validate against one supported UI contract.",
                ],
                [
                    "question" => "What happens when FNLLA Web changes upstream?",
                    "answer" => "The runtime is resynced into `public/vendor/fnlla-web/`, then the starter validates the UI contract and version metadata before release work continues.",
                ],
            ],
        ]);
    }

    public function about(Request $request): Response
    {
        return $this->view("pages/about", [
            "pageTitle" => "About",
            "pageTitleSection" => "Framework",
            "principles" => [
                "Keep the runtime small enough that the whole request flow is easy to trace.",
                "Prefer local, published assets over external dependencies for the UI layer.",
                "Use plain PHP for templates so teams can onboard quickly without a custom DSL.",
                "Add production primitives deliberately: middleware, DI, validation, auth, logging and migrations.",
            ],
            "timelineItems" => [
                [
                    "title" => "Request enters through the public edge",
                    "text" => "The maintained public surface stays narrow: `public/index.php` for requests and `public/router.php` for the local PHP server.",
                    "meta" => "Public entrypoints",
                ],
                [
                    "title" => "Bootstrap wires the application intentionally",
                    "text" => "Environment loading, container setup, session handling and route registration stay readable in `bootstrap/` rather than being hidden behind generated caches.",
                    "meta" => "Bootstrap and container",
                ],
                [
                    "title" => "Controller and view keep the delivery logic visible",
                    "text" => "Controllers prepare data, validation and redirects while plain PHP views render inside one shared FNLLA Web shell.",
                    "meta" => "Delivery layer",
                ],
            ],
            "starterBoundaries" => [
                [
                    "title" => "Framework repo stays maintainable",
                    "text" => "Use `fnlla/php` to improve shared routing, docs, guards, scripts and version contracts for every future project.",
                ],
                [
                    "title" => "Exported projects stay delivery-focused",
                    "text" => "Use `make:project` to create the actual website or application repository without inheriting the full maintainer docs workspace.",
                ],
                [
                    "title" => "FNLLA Web remains one-way dependency",
                    "text" => "The starter consumes FNLLA Web as a vendored runtime. It does not fork the UI contract into multiple parallel styling systems.",
                ],
            ],
        ]);
    }

    public function contact(Request $request): Response
    {
        return $this->view("pages/contact", [
            "pageTitle" => "Contact",
            "pageTitleSection" => "Framework",
            "engagementTracks" => [
                [
                    "title" => "Platform advisory",
                    "text" => "Use this when the team needs help shaping the architecture, delivery boundaries or repo strategy before larger implementation work starts.",
                ],
                [
                    "title" => "Implementation support",
                    "text" => "Use this for page building, controller work, forms, auth flows, migrations and operational hardening inside one real FNLLA PHP project.",
                ],
                [
                    "title" => "Operational support",
                    "text" => "Use this when the project already exists and the current need is runtime sync, validation, release hygiene or maintainability cleanup.",
                ],
            ],
            "deliverySteps" => [
                [
                    "number" => "1",
                    "title" => "Scope the request",
                    "text" => "Define the page map, auth boundary, data needs and runtime assumptions early so the build sequence stays clear.",
                ],
                [
                    "number" => "2",
                    "title" => "Build the application surface",
                    "text" => "Routes, controllers, views, forms and persistence are added with FNLLA Web already solving the shared interaction layer.",
                ],
                [
                    "number" => "3",
                    "title" => "Validate before release",
                    "text" => "Run FNLLA Web checks, tests, lint and version verification before any commit, deployment or handoff candidate is called ready.",
                ],
            ],
            "contactFaqs" => [
                [
                    "question" => "Is this form wired as a real example or only visual markup?",
                    "answer" => "It is a real starter flow: CSRF, validation, flashed input, flashed status and redirect-after-post are all active in the example.",
                ],
                [
                    "question" => "What should a downstream team replace first here?",
                    "answer" => "The content, service tracks, validation copy and mail destination should all be replaced with the real project context early in delivery.",
                ],
            ],
        ]);
    }

    public function sendContact(Request $request): Response
    {
        $payload = [
            "name" => trim((string) $request->input("name", "")),
            "company" => trim((string) $request->input("company", "")),
            "email" => trim((string) $request->input("email", "")),
            "scope" => trim((string) $request->input("scope", "")),
            "brief" => trim((string) $request->input("brief", "")),
        ];

        try {
            $this->validate($payload, [
                "name" => ["required", "string", "min:2", "max:120"],
                "company" => ["nullable", "string", "max:120"],
                "email" => ["required", "email", "max:160"],
                "scope" => ["required", "in:Platform advisory,Implementation support,Operational support"],
                "brief" => ["required", "string", "min:12", "max:3000"],
            ]);
        } catch (ValidationException $exception) {
            flash_set("old", $payload);
            flash_set("errors", $exception->errors());
            flash_set("status", [
                "variant" => "warning",
                "title" => "A few fields still need attention",
                "text" => "Review the highlighted inputs and submit the form again.",
                "toast" => false,
            ]);
            regenerate_csrf_token();

            return $this->redirect(route("contact") . "#contact-form");
        }

        flash_set("status", [
            "variant" => "success",
            "title" => "Request captured",
            "text" => "The example form completed successfully and the framework flashed the confirmation into the next request.",
            "toast" => true,
        ]);
        mailer()->to((string) env("CONTACT_NOTIFICATION_EMAIL", "team@example.com"))->send(
            "New contact form submission",
            "<p><strong>Name:</strong> " . h($payload["name"]) . "</p><p><strong>Email:</strong> " . h($payload["email"]) . "</p><p><strong>Scope:</strong> " . h($payload["scope"]) . "</p><p><strong>Brief:</strong> " . nl2br(h($payload["brief"])) . "</p>",
            "Name: {$payload["name"]}\nEmail: {$payload["email"]}\nScope: {$payload["scope"]}\nBrief: {$payload["brief"]}"
        );
        event("contact.form.submitted", [
            "payload" => $payload,
        ]);
        regenerate_csrf_token();

        return $this->redirect(route("contact") . "#contact-form");
    }
}
