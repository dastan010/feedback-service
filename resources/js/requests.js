const axios = window.axios
const BASE_API_URL = 'http://localhost:8000'
export default {
  getAllTickets: () =>
    axios.get(`${BASE_API_URL}/tickets`),
  createTicket: (ticket) =>
    axios.post(`${BASE_API_URL}/tickets`, ticket)  
}