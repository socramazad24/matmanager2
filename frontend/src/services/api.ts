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
      // ⚠️ Si la sesión expiró, devolvemos un mensaje claro
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
      body: JSON.stringify(material),
    });
  }

  static async updateMaterial(id: string, material: any) {
    return this.request<{ success: boolean; message: string }>('/materials/update.php', {
      method: 'POST',
      body: JSON.stringify({ idMaterial: id, ...material }),
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

  static async deleteUser(id: string) {
  return this.request('/users/delete.php', {
    method: 'POST', 
    body: JSON.stringify({ idEmployee: id }),
  });
}

  static async deleteProvider(id: string) {
  return this.request('/providers/delete.php', {
    method: 'POST',
    body: JSON.stringify({ idProvider: id }),
  });
}

static async deleteMaterial(id: string) {
  return this.request('/materials/delete.php', {
    method: 'POST',
    body: JSON.stringify({ idMaterial: id }),
  });
}

static async deleteOrder(id: string) {
  return this.request('/orders/delete.php', {
    method: 'POST',
    body: JSON.stringify({ idOrder: id }),
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
}

