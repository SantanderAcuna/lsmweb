# syntax=docker/dockerfile:1.7
# ---------------------------------------------------------------------------
# Frontend Vue 3 + Vite — Multi-stage build
# Stage 1: build the SPA with Node 20.
# Stage 2: serve static `dist/` with nginx:alpine (no Node runtime in final image).
# ---------------------------------------------------------------------------

# ---------- Stage 1: Build ----------
FROM node:20-alpine AS builder

WORKDIR /app

# Copy lockfiles first to maximise layer cache for deps.
COPY package.json package-lock.json* ./

# `npm ci` is reproducible and faster than `npm install` for CI/builds.
RUN npm ci --no-audit --no-fund

# Copy the rest of the source and produce the production bundle.
COPY . .

# Vite build (also runs vue-tsc --noEmit per package.json).
RUN npm run build

# ---------- Stage 2: Runtime (nginx) ----------
FROM nginx:alpine AS runtime

# Replace the default config with our SPA-aware one.
RUN rm -f /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copy the built static assets from the builder stage.
COPY --from=builder /app/dist /usr/share/nginx/html

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD wget --quiet --spider http://127.0.0.1/ || exit 1

# nginx:alpine already defines a sane CMD; keep default to avoid drift.
