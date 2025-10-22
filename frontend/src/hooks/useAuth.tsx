import { createContext, useContext, useState, useEffect } from 'react';
import { ApiService } from '@/services/api';

// 🧠 Tipo del usuario
interface User {
  username: string;
  role: string;
}

// 🧠 Tipo de respuesta del backend para login y check
interface AuthResponse {
  status: 'success' | 'error';
  message: string;
  user?: User;
}

// 🧠 Contexto global
interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (username: string, password: string) => Promise<AuthResponse>;
  logout: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType>({
  user: null,
  loading: true,
  login: async () => ({ status: 'error', message: 'No implementado' }),
  logout: async () => {},
});

export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    ApiService.checkAuth()
      .then((res: AuthResponse) => {
        if (res.status === 'success' && res.user) {
          setUser(res.user);
        }
      })
      .finally(() => setLoading(false));
  }, []);

  // ✅ Ahora login devuelve AuthResponse
  const login = async (username: string, password: string): Promise<AuthResponse> => {
    const res = await ApiService.login(username, password);

    if (res.status === 'success' && res.user) {
      setUser(res.user);
    }

    return res;
  };

  const logout = async () => {
    await ApiService.logout();
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
