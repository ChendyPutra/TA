import React from 'react'
import { Routes, Route, Link } from 'react-router-dom'
import Home from './pages/Home'

export default function App() {
  return (
    <div className="min-h-screen bg-blue-50 text-gray-800">
      <nav className="bg-orange-400 text-white p-4">
        <div className="container mx-auto flex justify-between">
          <Link to="/" className="font-bold">EduLearnt</Link>
        </div>
      </nav>
      <Routes>
        <Route path="/" element={<Home />} />
      </Routes>
    </div>
  )
}