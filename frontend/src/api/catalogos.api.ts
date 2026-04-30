import { http } from './http'
import type { Dependencia } from '@/types/dependencia'
import type { Usuario } from '@/types/usuario'
import type { Paginated } from '@/types/servidor'

/**
 * Cliente mínimo para catálogos reutilizables (selectores).
 *
 * NOTA importante de alcance:
 *   - Para `municipios` y `paises` el backend actual NO expone aún endpoints
 *     `/municipios` ni `/paises` (no existen en routes/api.php).
 *     Cuando se creen, llenar la implementación TODO.
 *     Mientras tanto, los componentes Select asociados deben caer al fallback
 *     de input numérico (ver `SelectMunicipio.vue` y `SelectPais.vue`).
 */

export interface OpcionCatalogo {
  id: number
  nombre: string
}

export const CatalogosApi = {
  /**
   * Dependencias activas: usa el endpoint admin paginado pidiendo activas + per_page alto.
   * Requiere sesión admin (los selectores se usan dentro del admin).
   */
  async dependenciasActivas(): Promise<Dependencia[]> {
    const { data } = await http.get<Paginated<Dependencia>>('/admin/dependencias', {
      params: { activo: 1, per_page: 200 }
    })
    return data.data
  },

  /**
   * Lista usuarios activos (paginada, traemos hasta 200 para el selector).
   */
  async usuariosActivos(): Promise<Usuario[]> {
    const { data } = await http.get<Paginated<Usuario>>('/admin/usuarios', {
      params: { estado: 'ACTIVO', per_page: 200 }
    })
    return data.data
  },

  /**
   * TODO: implementar cuando exista `/municipios` en backend.
   *   Mientras tanto se retorna `null` y el componente cae al input numérico.
   */
  async municipiosTodos(): Promise<OpcionCatalogo[] | null> {
    // TODO(backend): exponer GET /api/v1/municipios.
    // Cuando exista:
    //   const { data } = await http.get<{ data: OpcionCatalogo[] }>('/municipios')
    //   return data.data
    return null
  },

  /**
   * TODO: implementar cuando exista `/paises` en backend.
   *   Mientras tanto se retorna `null` y el componente cae al input numérico.
   */
  async paisesTodos(): Promise<OpcionCatalogo[] | null> {
    // TODO(backend): exponer GET /api/v1/paises.
    return null
  }
}
