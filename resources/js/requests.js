const axios = window.axios
const BASE_API_URL = 'http://localhost:8000',
      ADMIN_BASE_API_URL = 'http://localhost:8000/admin'
export default {
  getUsers: () =>
    axios.get(`${ADMIN_BASE_API_URL}/users`),
  getUserTickets: user_id => 
    axios.get(`${ADMIN_BASE_API_URL}/users/getTickets/${user_id}`),
  responseToTicket: (ticket_id, data) =>
    axios.put(`${ADMIN_BASE_API_URL}/users/ticketResponse/${ticket_id}`, data),
  getAllTickets: () =>
    axios.get(`${BASE_API_URL}/tickets`),
  createTicket: formData =>
    axios.post(`${BASE_API_URL}/tickets`, formData),
  downloadFile: () =>
    axios.get(`${ADMIN_BASE_API_URL}/users/fileDownload`, {
      // responseType: 'blob'
    })  
}