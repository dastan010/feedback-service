const axios = window.axios
const BASE_API_URL = 'http://127.0.0.1:8000',
      ADMIN_BASE_API_URL = 'http://127.0.0.1:8000/admin'
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
  downloadFile: (ticket_id, user_id) =>
    axios.get(`${ADMIN_BASE_API_URL}/users/${user_id}/tickets/${ticket_id}/fileDownload`, {
      responseType: 'blob'
    })  
}