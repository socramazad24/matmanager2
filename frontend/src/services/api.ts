const API_BASE_URL = '/api';

export class ApiService {
  // 🔧 Método genérico para hacer peticiones con manejo de sesión
  private static async request<T>(
    endpoint: string,
    options: RequestInit = {}
  ): Promise<T> {
    const response = await fetch(`${API_BASE_URL}${endpoint}`, {
      ...options,
      mode: 'cors', // 🔥 necesario para que viaje la cookie entre puertos
      credentials: 'include', // 🔥 mantiene la sesión PHP
      headers: {
        'Content-Type': 'application/json',
        ...options.headers,
      },
    });

    let data: any = {};
    try {
      data = await response.json();
    } catch (err) {
      throw new Error('Respuesta inválida del servidor');
    }

    if (!response.ok) {
      if (response.status === 401) {
        throw new Error('No autenticado. Por favor inicie sesión.');
      }
      throw new Error(data.message || 'Error en la petición');
    }

    return data as T;
  }

  // 🧩 AUTENTICACIÓN
  static async login(username: string, password: string) {
    return this.request<{ status: string; message: string; user?: any }>(
      '/auth/login.php',
      {
        method: 'POST',
        body: JSON.stringify({ username, password }),
      }
    );
  }

  static async logout() {
    return this.request<{ status: string; message: string }>('/auth/logout.php', {
      method: 'POST',
    });
  }

  static async checkAuth() {
    return this.request<{ status: string; user?: any }>('/auth/check.php');
  }

  // 🧩 MATERIALES
  static async getMaterials() {
    return this.request<{ success: boolean; data: any[] }>('/materials/list.php');
  }

 static async createMaterial(material: any) {
  return this.request<{ success: boolean; message: string }>('/materials/create.php', {
    method: 'POST',
    body: JSON.stringify(material), // Enviamos TODO el objeto completo
  });
}

  static async updateMaterial(id: string, material: any) {
    return this.request<{ success: boolean; message: string }>('/materials/update.php', {
      method: 'POST',
      body: JSON.stringify({ idMaterial: id, ...material }),
    });
  }

  static async deleteMaterial(id: string) {
  return this.request<{ success: boolean; message: string }>('/materials/delete.php', {
    method: 'POST',
    body: JSON.stringify({ idMaterial: id }), // ✅ Envía el campo correcto
  });
}

  // 🧩 USUARIOS
  static async getUsers() {
    return this.request<{ success: boolean; data: any[] }>('/users/list.php');
  }

  static async createUser(user: any) {
    return this.request<{ success: boolean; message: string }>('/users/create.php', {
      method: 'POST',
      body: JSON.stringify(user),
    });
  }

  static async updateUser(id: string, user: any) {
    return this.request<{ success: boolean; message: string }>('/users/update.php', {
      method: 'POST',
      body: JSON.stringify({ idEmployee: id, ...user }),
    });
  }

  static async deleteUser(id: string | number) {
    return this.request<{ success: boolean; message: string }>('/users/delete.php', {
      method: 'POST',
      body: JSON.stringify({ idEmployee: Number(id) }),
    });
  }

  // 🧩 PROVEEDORES
  static async getProviders() {
    return this.request<{ success: boolean; data: any[] }>('/providers/list.php');
  }

  static async createProvider(provider: any) {
    return this.request<{ success: boolean; message: string }>('/providers/create.php', {
      method: 'POST',
      body: JSON.stringify(provider),
    });
  }

  static async deleteProvider(id: string | number) {
    return this.request<{ success: boolean; message: string }>('/providers/delete.php', {
      method: 'POST',
      body: JSON.stringify({ idProveedor: Number(id) }), // ⚠️ igual que backend
    });
  }

  // 🧩 PEDIDOS
  static async getOrders() {
    return this.request<{ success: boolean; data: any[] }>('/orders/list.php');
  }

  static async createOrder(order: any) {
    return this.request<{ success: boolean; message: string }>('/orders/create.php', {
      method: 'POST',
      body: JSON.stringify(order),
    });
  }

  static async deleteOrder(id: string | number) {
    return this.request<{ success: boolean; message: string }>('/orders/delete.php', {
      method: 'POST',
      body: JSON.stringify({ idPedido: Number(id) }),
    });
  }
}
