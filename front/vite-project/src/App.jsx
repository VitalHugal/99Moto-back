import { BrowserRouter } from 'react-router-dom';
// Components
import AppRoutes from './routes';

export default function App() {

  return (
    <>
      <BrowserRouter>
          <AppRoutes />
      </BrowserRouter>
    </>
  );
}

